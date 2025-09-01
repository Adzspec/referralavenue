<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Category;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Store;
use App\Services\AdtractionApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\Middleware\RateLimited; // ⬅️ add
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessStoreOfferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $storeId;
    protected string $token;

    public $tries = 3;
    public $backoff = [60, 300, 900];
    public $timeout = 600;

    public function __construct(int $storeId, string $token)
    {
        $this->storeId = $storeId;
        $this->token   = $token;
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping("adtraction:offers:store:{$this->storeId}"))->expireAfter(900),
            // ⬇️ throttle globally to avoid bursts that trigger 429
            new RateLimited('adtraction-api'),
        ];
    }

    public function handle(): void
    {
        ini_set('memory_limit', '512M');

        /** @var Store|null $store */
        $store = Store::find($this->storeId);
        if (!$store) {
            Log::warning('Adtraction offers: store not found', ['store_id' => $this->storeId]);
            return;
        }

        /** @var Company|null $company */
        $company = $store->company()->first();
        if (!$company) {
            Log::warning('Adtraction offers: company not found for store', ['store_id' => $store->id]);
            return;
        }

        $api = new AdtractionApiService($this->token);

        $body = [
            'programId' => $store->programId,
            'channelId' => $store->channelId,
            'feedId'    => $store->productFeedId,
            'setEpi'    => true,
            'gt'        => false,
        ];

        Log::debug('Fetching products from API', ['store_id' => $store->id]);

        $products = $this->safeRequest($api, '/partner/products/feed/', [], 'POST', $body, 'v3');

        // ⬇️ if we got rate-limited and re-queued, stop now
        if (isset($products['__released__'])) {
            return;
        }

        if (!is_array($products)) {
            Log::warning('Adtraction offers: invalid response format', ['store_id' => $store->id]);
            return;
        }

        if (empty($products)) {
            Log::info('Adtraction offers: empty feed', ['store_id' => $store->id]);
            return;
        }

        Log::debug('Processing products', ['count' => count($products), 'store_id' => $store->id]);
        $categoryId     = $this->ensureCategoryForStore($store, $store->categoryName);
        $totalProcessed = 0;

        foreach (array_chunk($products, 250) as $chunkIndex => $productChunk) {
            $rows = [];

            foreach ($productChunk as $p) {
                $sku = data_get($p, 'sku');
                if (!$sku) continue;

                $old   = (float) (data_get($p, 'oldPrice') ?? 0);
                $price = (float) (data_get($p, 'productPrice') ?? 0);

                $discount = ($old > 0 && $price >= 0 && $price <= $old)
                    ? (int) round((($old - $price) / $old) * 100)
                    : null;

                $titleBase = (string) data_get($p, 'productName', '');
                $title = $discount && $discount > 0
                    ? 'Upp till ' . $discount . '% Rabatter ' . $titleBase
                    : ($titleBase ?: null);

                $rows[] = [
                    'company_id'    => (int) $company->id,
                    'store_id'      => (int) $store->id,
                    'category_id'   => $categoryId,
                    'title'         => $title,
                    'description'   => data_get($p, 'productDescription'),
                    'product_url'   => data_get($p, 'productUrl'),
                    'sku'           => (string) $sku,
                    'link'          => data_get($p, 'trackingUrl'),
                    'is_deal'       => 1,
                    'path'          => Str::slug($titleBase ?: (string) $sku, '-'),
                    'thumbnail'     => data_get($p, 'imageUrl'),
                    'product_name'  => data_get($p, 'productName'),
                    'product_price' => $price ?: null,
                    'old_price'     => $old ?: null,
                    'status'        => 1,
                    'updated_at'    => now(),
                    'created_at'    => now(),
                ];
            }

            if ($rows) {
                Offer::upsert(
                    $rows,
                    ['company_id', 'store_id', 'sku'],
                    [
                        'title','description','link','is_deal','path','thumbnail',
                        'product_name','product_price','old_price','product_url',
                        'status','category_id','updated_at'
                    ]
                );
                $totalProcessed += count($rows);

                Log::debug('Processed chunk', [
                    'chunk'    => $chunkIndex,
                    'processed'=> $totalProcessed,
                    'memory'   => round(memory_get_usage(true) / 1024 / 1024, 1) . 'MB',
                ]);

                unset($rows);
                gc_collect_cycles();
            }
        }

        Log::info('Adtraction offers: upserted products', [
            'store_id'     => $store->id,
            'count'        => $totalProcessed,
            'peak_memory'  => round(memory_get_peak_usage(true) / 1024 / 1024, 1) . 'MB',
        ]);
    }

    protected function ensureCategoryForStore(Store $store, ?string $name): ?int
    {
        $name = $name ? trim($name) : null;
        if (!$name) return null;

        $category = Category::firstOrCreate(
            ['company_id' => $store->company_id, 'name' => $name],
            ['name' => $name]
        );

        return $category->id;
    }

    protected function safeRequest(
        AdtractionApiService $api,
        string $endpoint,
        array $queryParams = [],
        string $method = 'GET',
        array $body = [],
        ?string $version = null
    ): array {
        try {
            $res = $api->request(
                endpoint: $endpoint,
                queryParams: $queryParams,
                method: $method,
                body: $body,
                version: $version
            );
            return is_array($res) ? $res : [];
        } catch (\Throwable $e) {
            $msg = $e->getMessage();

            // ⬇️ If rate-limited, release the job to run after the reset time
            if (str_contains($msg, '429')) {
                $delay = $this->extractRateLimitDelaySeconds($msg);
                Log::notice('Adtraction 429: releasing job', [
                    'store_id' => $this->storeId,
                    'delay_s'  => $delay,
                    'error'    => $msg,
                ]);
                $this->release($delay);
                // Return a marker so handle() stops cleanly
                return ['__released__' => true];
            }

            Log::warning('Adtraction offers request failed', [
                'endpoint' => $endpoint,
                'store_id' => $this->storeId,
                'error'    => $msg,
            ]);
            return [];
        }
    }

    /**
     * Parse "Reset time 1755600193444" (ms epoch) from API error and compute delay.
     * Returns a bounded delay between 5s and 300s.
     */
    protected function extractRateLimitDelaySeconds(string $message): int
    {
        $nowMs = (int) floor(microtime(true) * 1000);
        if (preg_match('/(\d{13})/', $message, $m)) {
            $resetMs = (int) $m[1];
            $delta   = max(0, (int) ceil(($resetMs - $nowMs) / 1000));
            return max(5, min($delta, 300));
        }
        // Fallback if timestamp isn’t present
        return 30;
    }

    public function failed(\Throwable $e): void
    {
        Log::error('ProcessStoreOfferJob failed', [
            'store_id' => $this->storeId,
            'error'    => $e->getMessage(),
        ]);
    }
}

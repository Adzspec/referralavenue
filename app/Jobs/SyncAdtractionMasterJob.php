<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Category;
use App\Models\Company;
use App\Models\Store;
use App\Services\AdtractionApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SyncAdtractionMasterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int|string */
    protected $channelId;
    /** @var int|string */
    protected $companyId;

    /** @var AdtractionApiService */
    protected $api;

    /** How many times to try the job */
    public $tries = 3;

    /** Backoffs between retries (seconds) */
    public $backoff = [60, 300, 900];

    /** Fail the job if it runs longer than this (seconds) */
    public $timeout = 120;

    public function __construct($channelId, $companyId)
    {
        $this->channelId = $channelId;
        $this->companyId = $companyId;
    }

    /**
     * Prevent concurrent runs for the same company+channel.
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping("adtraction:{$this->companyId}:{$this->channelId}"))
                ->expireAfter(600), // 10 min lock safety
        ];
    }

    public function handle(): void
    {
        $company = Company::find($this->companyId);

        if (!$company) {
            Log::error('Adtraction sync: company not found', ['company_id' => $this->companyId]);
            return;
        }

        $integration = $company->integrations()
            ->where('provider', 'adtraction')
            ->first();

        $token = data_get($integration, 'credentials.api_token');

        if (empty($integration) || empty($token)) {
            Log::warning('Adtraction sync: missing integration or token', ['company_id' => $company->id]);
            return;
        }

        $this->api = new AdtractionApiService($token);

        // 1) Fetch approved/active applications for this channel
        $applications = $this->safeRequest('/partner/applications/', [
            'channelId' => $this->channelId,
        ]);

        if (!is_array($applications) || empty($applications)) {
            Log::info('Adtraction sync: no applications returned', ['company_id' => $company->id]);
            return;
        }

        foreach ($applications as $app) {
            // Adjust this check if Adtraction uses a string status (e.g., "approved")
            $status = data_get($app, 'status');
            if (!$status) {
                continue;
            }

            $programId    = data_get($app, 'programId');
            $programName  = data_get($app, 'programName');
            $categoryName = data_get($app, 'categoryName');

            if (!$programId) {
                continue;
            }

            // 2) Ensure category exists (optional)
            if ($categoryName) {
                $this->ensureCategory($categoryName);
            }

            // Avoid duplicates per company+channel+program
            $already = Store::query()
                ->where('programId', $programId)
                ->where('channelName', 'adtraction')
                ->where('company_id', $this->companyId)
                ->exists();

            if ($already) {
                continue;
            }

            // 3) Fetch program details (logo, feed URL, etc.)
            $programData = $this->safeRequest('/partner/programs/', [
                'market'    => 'SE',
                'programId' => $programId,
                'channelId' => $this->channelId,
            ]);

            $program0 = is_array($programData) ? ($programData[0] ?? []) : [];
            $feedUrl  = data_get($program0, 'feedURL');
            $logo     = data_get($program0, 'logoURL');

            $pfid = null;
            if ($feedUrl) {
                $parts = parse_url($feedUrl);
                if (!empty($parts['query'])) {
                    parse_str($parts['query'], $queryParams);
                    $pfid = $queryParams['pfid'] ?? null;
                }
            }

            // 4) Upsert store
            Store::updateOrCreate(
                [
                    'programId'   => $programId,
                    'channelName' => 'adtraction',
                    'company_id' => $this->companyId
                ],
                [
                    'channelId'     => data_get($app, 'channelId'),
                    'status'        => $status,
                    'name'          => $programName,
                    'categoryName'  => $categoryName,
                    'categoryId'    => data_get($app, 'categoryId'),
                    'image'         => $logo,
                    'productFeedId' => $pfid,
                ]
            );
        }

        SyncOffersAdtraction::dispatch($this->channelId, $this->companyId);
        Log::info('Adtraction sync: stores synced successfully', [
            'company_id' => $this->companyId,
            'channel_id' => $this->channelId,
        ]);
    }

    /**
     * Called if the job ultimately fails.
     */
    public function failed(\Throwable $e): void
    {
        Log::error('Adtraction sync failed', [
            'company_id' => $this->companyId,
            'channel_id' => $this->channelId,
            'error'      => $e->getMessage(),
        ]);
    }

    /**
     * Create/ensure category by name.
     */
    protected function ensureCategory(string $categoryName): Category
    {
        return Category::firstOrCreate(
            ['company_id' => $this->companyId, 'name' => $categoryName]
        );
    }

    /**
     * Wrapper to safely call the API and log any exception.
     */
    protected function safeRequest(string $endpoint, array $queryParams = []): array
    {
        try {
            $res = $this->api->request(endpoint: $endpoint, queryParams: $queryParams);
            return is_array($res) ? $res : [];
        } catch (\Throwable $e) {
            Log::warning('Adtraction API request failed', [
                'endpoint' => $endpoint,
                'params'   => $queryParams,
                'error'    => $e->getMessage(),
            ]);
            return [];
        }
    }
}

<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Store;
use App\Services\AddrevenueApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncAddrevenueProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $companyId;
    protected $channelId;
    protected $token;
    protected $page;
    protected $limit;

    public function __construct($companyId, $channelId, $token, $page, $limit)
    {
        $this->companyId = $companyId;
        $this->channelId = $channelId;
        $this->token     = $token;
        $this->page      = $page;
        $this->limit     = $limit;
    }

    public function handle()
    {
        $apiService = new AddrevenueApiService($this->token);
        $response = $apiService->request(
            endpoint: '/products',
            queryParams: [
                'channelId' => $this->channelId,
                'market'    => 'SE',
                'limit'     => $this->limit,
                'offset'    => $this->page,
            ],
            method: 'GET',
            body: []
        );
        $products = $response['results'] ?? [];

        if (empty($products)) {
            return;
        }

        $advertiserIds = array_unique(array_column($products, 'advertiserId'));
        $stores = Store::query()
            ->where('company_id', $this->companyId)
            ->whereIn('programId', $advertiserIds)
            ->get()
            ->keyBy('programId');

        $skus = array_column($products, 'sku');
        $existingSkus = Offer::query()
            ->where('company_id', $this->companyId)
            ->whereIn('sku', $skus)
            ->pluck('sku')
            ->toArray();
        $existingSkuSet = array_flip($existingSkus);

        foreach ($products as $product) {
            if (empty($product['image_link'])) continue;
            if (!empty($product['sku']) && isset($existingSkuSet[$product['sku']])) continue;
            if (empty($product['advertiserId']) || !isset($stores[$product['advertiserId']])) continue;
            $store = $stores[$product['advertiserId']];

            $discount = 0;
            if (
                isset($product['price'], $product['sale_price']) &&
                is_numeric($product['price']) && is_numeric($product['sale_price']) &&
                $product['price'] > 0 && $product['price'] != $product['sale_price']
            ) {
                $discount = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
            }
            $categoryId = $this->getCategory($store);
            $title = isset($product['title'])
                ? ($discount > 0 ? 'Upp till ' . $discount . '% Rabatter ' . $product['title'] : $product['title'])
                : ($discount > 0 ? 'Upp till ' . $discount . '% Rabatter' : 'Deal');
            $path = isset($product['title'])
                ? preg_replace('/[^a-zA-Z0-9]/', '', $product['title'])
                : 'deal-' . time();

            $deal = new Offer([
                'title'         => $title,
                'path'          => $path,
                'product_name'  => $product['title'] ?? null,
                'description'   => $product['description'] ?? null,
                'store_id'      => $store->id,
                'link'          => $product['trackingLink'] ?? ($product['link'] ?? null),
                'is_deal'       => 1,
                'thumbnail'     => $product['image_link'] ?? null,
                'sku'           => $product['sku'] ?? null,
                'product_price' => $product['sale_price'] ?? ($product['price'] ?? null),
                'old_price'     => $product['price'] ?? null,
                'product_url'   => $product['link'] ?? null,
                'source'        => 'addrevenue',
                'status'        => 1,
            ]);
            $deal->store()->associate($store->id);
            $deal->company()->associate($this->companyId);
            $deal->category()->associate($categoryId);

            try {
                $deal->save();
            } catch (\Exception $e) {
                \Log::warning("Failed to save deal with SKU " . ($product['sku'] ?? '-') . ": " . $e->getMessage());
            }
        }
        unset($products, $stores, $existingSkus, $existingSkuSet, $response);
        gc_collect_cycles();
    }

    public function getCategory($store)
    {
        return Category::query()
            ->where('company_id', $this->companyId)
            ->where('name',$store->categoryName)->first()->id;
    }
}

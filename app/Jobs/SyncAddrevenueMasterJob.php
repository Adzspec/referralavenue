<?php

namespace App\Jobs;

use App\Models\Company;
use App\Services\AddrevenueApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncAddrevenueMasterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $channelId;
    protected $companyId;

    public function __construct($channelId, $companyId)
    {
        $this->channelId = $channelId;
        $this->companyId = $companyId;
    }

    public function handle()
    {
        // Get the token for this company
        $company = \App\Models\Company::find($this->companyId);
        if (!$company) {
            \Log::error("Company not found for Addrevenue sync: {$this->companyId}");
            return;
        }
        $integration = $company->integrations()->where('provider', 'addrevenue')->first();
        if (!$integration || empty($integration->credentials['api_token'])) {
            \Log::error("No Addrevenue integration found for company: {$company->id}");
            return;
        }
        $token = $integration->credentials['api_token'];
        $apiService = new AddrevenueApiService($token);

        // Fetch stores/advertisers if needed
        $advertiserResponse = $apiService->request(
            endpoint: '/advertisers',
            queryParams: [
                'channelId' => $this->channelId,
            ],
            method: 'GET',
            body: []
        );
        $stores = $advertiserResponse['results'] ?? [];
        foreach (array_chunk($stores, 500) as $chunk) {
            ProcessStoreChunkJob::dispatch($this->companyId, $this->channelId, $chunk);
        }

        // Get total products and dispatch jobs by page
        $limit = 900;
        $firstResponse = $apiService->request(
            endpoint: '/products',
            queryParams: [
                'channelId' => $this->channelId,
                'market'    => 'SE',
                'limit'     => $limit,
                'offset'    => 1,
            ],
            method: 'GET',
            body: []
        );
        $totalCount = $firstResponse['totalCount'] ?? count($firstResponse['results']);
        $totalPages = ceil($totalCount / $limit);

        for ($page = 1; $page <= $totalPages; $page++) {
            SyncAddrevenueProductsJob::dispatch(
                $this->companyId,
                $this->channelId,
                $token,
                $page,
                $limit
            );
        }
    }
}

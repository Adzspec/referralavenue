<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessStoreChunkJob;
use App\Jobs\SyncAddrevenueCampaignsJob;
use App\Jobs\SyncAddrevenueMasterJob;
use App\Jobs\SyncAddrevenueProductsJob;
use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Store;
use App\Services\AddrevenueApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;


class AddrevenueController extends Controller
{
//    protected AddrevenueApiService $apiService;
    protected $channelId;
    protected $company;
    protected $integration;


    public function __construct()
    {
//        $this->apiService = $apiService;
        $this->company = auth()->user()->company;
        $this->integration = $this->company->integrations()->where('provider', 'addrevenue')->first();
        $this->channelId = $this->integration->credentials['channel_id'];
    }
    public function startSync(): RedirectResponse
    {
//        if (!$this->integration || !$this->integration->status) {
//            return redirect()->back()->withErrors(['message' => 'No Addrevenue credentials found.']);
//        }
        SyncAddrevenueMasterJob::dispatch($this->channelId, $this->company->id);
//        $this->dispatchStoresJobs();
//        $this->getCampaigns();

        return back()->with('success', 'Addrevenue sync started!');
    }

//    public function dispatchStoresJobs(): void
//    {
//        $response = $this->apiService->request(
//            endpoint: '/advertisers',
//            queryParams: ['channelId' => $this->channelId],
//        );
//
//        $stores = $response['results'] ?? [];
//        foreach (array_chunk($stores, 100) as $chunk) {
//            ProcessStoreChunkJob::dispatch($this->company->id, $this->integration->credentials, $chunk);
//        }
//    }

//    public function getCampaigns()
//    {
//        try {
//            $response = $this->apiService->request(
//                endpoint: '/campaigns',
//                queryParams: ['channelId' => $this->channelId]
//            );
//
//            SyncAddrevenueCampaignsJob::dispatch($response['results'], $this->channelId);
//
////            return response()->json([
////                'message' => 'Campaigns sync is in progress. Check back later for updates.'
////            ]);
//        } catch (\Exception $e) {
//            return response()->json([
//                'error' => 'Failed to start campaign sync.',
//                'details' => $e->getMessage()
//            ], 500);
//        }
//    }


    public function getProducts()
    {
        $limit = 500;
        $offset = 0;
        $company = \App\Models\Company::find($this->company->id);
        if (!$company) {
            \Log::error("Company not found for Addrevenue sync: {$this->company->id}");
            return;
        }
        $integration = $company->integrations()->where('provider', 'addrevenue')->first();
        if (!$integration || empty($integration->credentials['api_token'])) {
            \Log::error("No Addrevenue integration found for company: {$this->company->id}");
            return;
        }
        $token = $integration->credentials['api_token'];

        $apiService = new AddrevenueApiService($token);
//
//        // First request to get totalCount if available
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
dd($firstResponse);
//        if (!$response || !is_array($response['results'])) {
//            return response()->json(['message' => 'Failed to fetch products.'], 400);
//        }
//
//        // Try to get totalCount from API, fallback to current batch size if not present
//        $totalCount = $response['totalCount'] ?? count($response['results']);
//
//        // Dispatch the first batch
//        SyncAddrevenueProductsJob::dispatch($response['results'], $this->company->id);
//
//        $offset++;
//
//        // Loop through remaining batches
//        while ($offset < $totalCount) {
//            $response = $this->apiService->request(
//                endpoint: '/products',
//                queryParams: [
//                    'channelId' => $this->channelId,
//                    'market' => 'SE',
//                    'limit' => $limit,
//                    'offset' => $offset,
//                ],
//                method: 'GET',
//                body: [],
//            );
//
//            if ($response && is_array($response['results']) && count($response['results']) > 0) {
//                SyncAddrevenueProductsJob::dispatch($response['results'], $this->company->id);
//            } else {
//                break;
//            }
//
//            $offset++;
//        }
//
//        return response()->json([
//            'message' => 'Jobs Added successfully.'
//        ]);
    }


}

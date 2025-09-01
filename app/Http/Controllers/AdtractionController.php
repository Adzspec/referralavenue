<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessStoreChunkJob;
use App\Jobs\SyncAddrevenueCampaignsJob;
use App\Jobs\SyncAddrevenueMasterJob;
use App\Jobs\SyncAddrevenueProductsJob;
use App\Jobs\SyncAdtractionMasterJob;
use App\Jobs\SyncOffersAdtraction;
use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Store;
use App\Services\AddrevenueApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;


class AdtractionController extends Controller
{
//    protected AddrevenueApiService $apiService;
    protected $channelId;
    protected $company;
    protected $integration;


    public function __construct()
    {
        $user = auth()->user();
        $this->company = $user?->company;

        if (!$this->company) {
            // Inertia-friendly response (for actions/pages)
            redirect()->back()->withErrors(['message' => 'No company found for current user.'])->throwResponse();
        }

        $this->integration = $this->company->integrations()->where('provider', 'adtraction')->first();

        if (!$this->integration) {
            redirect()->back()->withErrors(['message' => 'No adtraction integration found for this company.'])->throwResponse();
        }

        $this->channelId = $this->integration->credentials['channel_id'] ?? null;

        if (!$this->channelId) {
            redirect()->back()->withErrors(['message' => 'No channel_id set in adtraction integration credentials.'])->throwResponse();
        }
    }

    public function startSync(): RedirectResponse
    {
        if (!$this->integration || !$this->integration->status) {
            return redirect()->back()->withErrors(['message' => 'No adtraction credentials found.']);
        }
        SyncAdtractionMasterJob::dispatch($this->channelId, $this->company->id);

        return back()->with('success', 'Adtraction sync started!');
    }

    public function getProductFeed()
    {
        try {
            ini_set('max_execution_time', 300);
            $stores = Store::query()
                ->where('channelName','adtraction')
                ->whereNotNull('productFeedId')
                ->get();
            foreach ($stores as $store) {
                $response = $this->apiService->request(
                    endpoint: '/partner/products/feed/',
                    queryParams: [],
                    method: 'POST',
                    body: [
                        'programId' => $store->programId,
                        'channelId' => $this->channelId,
                        'feedId'=> $store->productFeedId,
                        'setEpi'    => true,
                        'gt'=>false,
                    ],
                    version: 'v3'
                );
                if ($response){
                    foreach ($response as $product) {
                        $this->createDeals($product,$store->programId,$store);
                    }
                }
            }

//            return response()->json($response);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Jobs\SyncAdtractionMasterJob;
//use App\Models\Coupon;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;

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

}

<?php
namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Subscription;
use App\Services\AddrevenueApiService;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {

        return Inertia::render('Welcome', [
            'plans' => Subscription::with(['featureValues.feature'])->get(),
            'features' => Feature::orderBy('id')->get(),
            'user' => auth()->user(),
        ]);
    }
    public function transactions()
    {
        $company = \App\Models\Company::find(1);
        $integration = $company->integrations()->where('provider', 'addrevenue')->first();
        if (!$integration || empty($integration->credentials['api_token'])) {
            return;
        }
        $token = $integration->credentials['api_token'];
        $apiService = new AddrevenueApiService($token);
        $advertiserResponse = $apiService->request(
            endpoint: '/transactions',
            queryParams: [
                'channelId' => $integration->credentials['channel_id']
            ],
            method: 'GET',
            body: []
        );
        dd($advertiserResponse);
    }
}

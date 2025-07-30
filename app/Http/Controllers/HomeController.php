<?php
namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\AddrevenueApiService;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $plans = Subscription::query()->where('status',1)->get();
        return Inertia::render('Welcome', [
            'plans' => $plans,
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

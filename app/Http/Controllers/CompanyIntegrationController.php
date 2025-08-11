<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyIntegration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyIntegrationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = CompanyIntegration::query()->where('company_id', $user->company->id);
        $integrations = $query
            ->get()
            ->keyBy('provider')
            ->toArray();
        $activeIntegrations = $query->where('status', true)->count();
        $planLimit = $user->company->latestSubscription->subscription->getFeatureValue('affiliate_network_integrations');
        return Inertia::render('frontend_settings/index', [
            ...$integrations,
            'can' => [
                'create' => $user->can('create company settings'),
                'edit' => $user->can('edit company settings'),
                'delete' => $user->can('delete company settings'),
            ],
            'canUseAffiliateNetwork' => $activeIntegrations < $planLimit,
            'affiliateNetworkLimit' => (int)$planLimit,
        ]);
    }

    public function store(Request $request)
    {
        // Allow only company admins
        if (!auth()->user()->hasRole('company admin')) {
            abort(403, 'Unauthorized');
        }

        // Force company_id to logged-in user's company_id
        $companyId = auth()->user()->company_id;

        $validated = $request->validate([
            'provider' => 'required|string',
            'credentials' => 'required|array',
        ]);

        CompanyIntegration::updateOrCreate(
            ['company_id' => $companyId, 'provider' => $validated['provider']],
            ['credentials' => $validated['credentials']]
        );

        return redirect()->back()->with('status', 'Integration saved.');
    }

    public function destroy(CompanyIntegration $companyIntegration)
    {
        $companyIntegration->delete();
        return redirect()->back()->with('status', 'Integration deleted.');
    }

    public function adtraction(Request $request)
    {
        if (!auth()->user()->hasRole('company admin')) {
            abort(403, 'Unauthorized');
        }

        $companyId = auth()->user()->company_id;
        $provider = 'adtraction';

        $validated = $request->validate([
            'credentials' => 'required',
            'status' => 'required|boolean',
        ]);

        CompanyIntegration::updateOrCreate(
            ['company_id' => $companyId, 'provider' => $provider],
            [
                'credentials' => $validated['credentials'],
                'status' => $validated['status'] ? true : false,
            ]
        );

        return redirect()->back()->with('status', 'Integration saved.');
    }
    public function addrevenue(Request $request)
    {
        if (!auth()->user()->hasRole('company admin')) {
            abort(403, 'Unauthorized');
        }

        $companyId = auth()->user()->company_id;
        $provider = 'addrevenue';

        $validated = $request->validate([
            'credentials' => 'required|array',
            'status' => 'required|boolean',
        ]);

        CompanyIntegration::updateOrCreate(
            ['company_id' => $companyId, 'provider' => $provider],
            [
                'credentials' => $validated['credentials'],
                'status' => $validated['status'] ? true : false,
            ]
        );

        return redirect()->back()->with('status', 'Integration saved.');
    }

    public function tradedoubler(Request $request)
    {
        if (!auth()->user()->hasRole('company admin')) {
            abort(403, 'Unauthorized');
        }

        $companyId = auth()->user()->company_id;
        $provider = 'tradedoubler';

        $validated = $request->validate([
            'credentials' => 'required|array',
            'status' => 'required|boolean',
        ]);

        CompanyIntegration::updateOrCreate(
            ['company_id' => $companyId, 'provider' => $provider],
            [
                'credentials' => $validated['credentials'],
                'status' => $validated['status'] ? true : false,
            ]
        );

        return redirect()->back()->with('status', 'Integration saved.');
    }


}

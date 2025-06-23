<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompanySubscriptionController extends Controller
{
    public function index()
    {
        return Inertia::render('company_subscriptions/index', [
            'companySubscriptions' => CompanySubscription::with(['company', 'subscription'])->paginate(10),
            'companies' => Company::all(['id', 'name']),
            'subscriptions' => Subscription::all(['id', 'name', 'price']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'subscription_id' => 'required|exists:subscriptions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,cancelled,expired',
        ]);

        CompanySubscription::create($request->all());

        return back()->with('success', 'Company subscription created.');
    }

    public function update(Request $request, CompanySubscription $companySubscription)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,cancelled,expired',
        ]);

        $companySubscription->update($request->all());

        return back()->with('success', 'Subscription updated.');
    }

    public function destroy(CompanySubscription $companySubscription)
    {
        $companySubscription->delete();

        return back()->with('success', 'Subscription deleted.');
    }

}

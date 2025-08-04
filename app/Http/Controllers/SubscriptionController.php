<?php

namespace App\Http\Controllers;

use App\Models\CompanySubscription;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class SubscriptionController extends Controller
{
    public function index(): Response
    {
        $subscriptions = Subscription::paginate(10)->withQueryString();
        return Inertia::render('subscriptions/index', [
            'subscriptions' => $subscriptions,
            'can' => [
                'create' => auth()->user()->can('create subscriptions'),
                'edit' => auth()->user()->can('edit subscriptions'),
                'delete' => auth()->user()->can('delete subscriptions'),
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('subscriptions/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_id' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'status' => 'required|boolean',
        ]);

        try {
            Subscription::create($validated);
            return redirect()->route('subscriptions.index')->with('success', 'Subscription plan created.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create subscription. Please try again.'])->withInput();
        }
    }


    public function edit(Subscription $subscription): Response
    {
        return Inertia::render('subscriptions/edit', [
            'subscription' => $subscription,
        ]);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_id' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'status' => 'required|boolean',
        ]);

        try {
            $subscription->update($validated);
            return redirect()->route('subscriptions.index')->with('success', 'Subscription plan updated.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update subscription. Please try again.'])->withInput();
        }
    }


    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index')->with('success', 'Subscription plan deleted.');
    }

    public function createCheckoutSession(Request $request)
    {
        $user = auth()->user();
        $plan = \App\Models\Subscription::findOrFail($request->plan_id);
        if ($plan->price == 0) {
            $alreadyFree = CompanySubscription::query()
                ->where('company_id', $user->company_id)
                ->where('subscription_id', $plan->id)
                ->where('status', 1)
                ->exists();

            if (!$alreadyFree) {
                CompanySubscription::query()
                    ->where('company_id', $user->company_id)
                    ->where('status', 1)
                    ->update(['status' => 0]);

                CompanySubscription::create([
                    'company_id' => $user->company_id,
                    'subscription_id' => 1,
                    'start_date' => now(),
                    'status' => 1,
                ]);
            }

            return redirect()->route('subscriptions.success')->with('success', 'Free plan activated!');
        }
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'customer_email' => $user->email, // or create/retrieve customer
            'line_items' => [[
                'price' => $plan->price_id,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => url('/subscriptions/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/'),
            'metadata' => [
                'plan_id' => $plan->id, // <-- THIS LINE is the key!
            ],
        ]);

        return Inertia::render('subscriptions/stripeRedirect', [
            'stripeUrl' => $session->url,
        ]);
    }

    public function cancelSubscription(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        $companySubscription = $company->latestSubscription; // or however you fetch it
        $stripeSubscriptionId = $companySubscription->stripe_subscription_id;

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $subscription = \Stripe\Subscription::update($stripeSubscriptionId, [
            'cancel_at_period_end' => true, // recommended: lets user use the plan until the period ends
        ]);

        // Optionally: Update local status (but webhooks will sync this too)
        $companySubscription->status = 'cancelling'; // or similar
        $companySubscription->save();

        return back()->with('success', 'Subscription will be cancelled at period end.');
    }

}

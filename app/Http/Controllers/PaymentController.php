<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createIntent(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        $company = Company::findOrFail($request->company_id);
        $subscription = Subscription::findOrFail($request->subscription_id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount' => (int)($subscription->price * 100), // Stripe expects cents
            'currency' => 'usd',
            'metadata' => [
                'company_id' => $company->id,
                'subscription_id' => $subscription->id,
            ],
        ]);

        return response()->json(['clientSecret' => $intent->client_secret]);
    }

    public function handleWebhook(Request $request)
    {

        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            \Log::error('Invalid payload', ['exception' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            \Log::error('Invalid signature', ['exception' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $planId = $session->metadata->plan_id ?? null;
                $plan = \App\Models\Subscription::findOrFail($planId);
//                \Log::info('checkout.session.completed received', ['session' => $session]);
//                \Log::info('checkout.session.completed received', ['subscription' => $session->subscription]);
                $user = \App\Models\User::where('email', $session->customer_email)->first();
                if ($user) {
                    try {
                        \App\Models\CompanySubscription::create([
                            'company_id' => $user->company_id,
                            'stripe_subscription_id' => $session->subscription,
                            'subscription_id' => $planId,
                            'start_date' => now(),
                            'end_date' => now()->addDays((int)$plan->duration),
                            'status' => 'active',
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Subscription create failed', ['message' => $e->getMessage()]);
                    }
                }
                break;

            case 'invoice.payment_succeeded':
//                $invoice = $event->data->object;
//                $user = User::where('email', $invoice->customer_email)->first();
//                // e.g. invoice.subscription, invoice.amount_paid, invoice.customer_email
//                // Find and update payment record
//                Payment::create([
//                    'company_id' => $user->company_id,
//                    'stripe_invoice_id' => $invoice->id,
//                    'amount' => $invoice->amount_paid / 100,
//                    'status' => 'paid',
//                    // ...other fields
//                ]);
//                break;

            // Handle other events as needed
            case 'customer.subscription.updated':
            case 'customer.subscription.deleted':
            $session = $event->data->object;
            $stripeSubscriptionId = $session->id;
//            \Log::info('checkout.session.delete received', ['session' => $session]);
//            \Log::info('checkout.session.delete received', ['subscription' => $stripeSubscriptionId]);
            $subscription = \App\Models\CompanySubscription::query()->where('stripe_subscription_id', $stripeSubscriptionId)->first();
//            \Log::info('checkout.session.delete received', ['subscription' => $subscription]);
            if ($subscription) {
                $subscription->update([
                    'status' => 'canceled', // or 'canceled', 'inactive'
                    'end_date' => now(), // Or set to period_end from Stripe if available
                ]);
            }
            break;
                // Update your CompanySubscription status here
                break;
        }
//        if ($event->type === 'payment_intent.succeeded') {
//            $intent = $event->data->object;
//            $companyId = $intent->metadata->company_id ?? null;
//            $subscriptionId = $intent->metadata->subscription_id ?? null;
//            if ($companyId && $subscriptionId) {
//                $companySubscription = CompanySubscription::create([
//                    'company_id' => $companyId,
//                    'subscription_id' => $subscriptionId,
//                    'start_date' => now(),
//                    'end_date' => now()->addDays(Subscription::find($subscriptionId)->duration),
//                    'status' => 'active',
//                ]);
//                Payment::create([
//                    'company_subscription_id' => $companySubscription->id,
//                    'amount' => $intent->amount / 100,
//                    'payment_method' => $intent->payment_method,
//                    'payment_status' => 'succeeded',
//                    'transaction_id' => $intent->id,
//                    'paid_at' => now(),
//                ]);
//            }
//        }
        return response('Webhook handled', 200);
    }
}

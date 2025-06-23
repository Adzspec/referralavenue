<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
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

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;
            $companyId = $intent->metadata->company_id ?? null;
            $subscriptionId = $intent->metadata->subscription_id ?? null;
            if ($companyId && $subscriptionId) {
                $companySubscription = CompanySubscription::create([
                    'company_id' => $companyId,
                    'subscription_id' => $subscriptionId,
                    'start_date' => now(),
                    'end_date' => now()->addDays(Subscription::find($subscriptionId)->duration),
                    'status' => 'active',
                ]);
                Payment::create([
                    'company_subscription_id' => $companySubscription->id,
                    'amount' => $intent->amount / 100,
                    'payment_method' => $intent->payment_method,
                    'payment_status' => 'succeeded',
                    'transaction_id' => $intent->id,
                    'paid_at' => now(),
                ]);
            }
        }
        return response('Webhook handled', 200);
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Subscription;
use App\Models\SubscriptionFeatureValue;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionFeatureController extends Controller
{
    public function index()
    {
        return Inertia::render('subscriptions/features', [
            'plans' => Subscription::with('featureValues.feature')->get(),
            'features' => Feature::all(),
        ]);
    }

    public function update(Request $request)
    {

        foreach ($request->input('matrix') as $planId => $featureMap) {

            foreach ($featureMap as $featureId => $value) {
                SubscriptionFeatureValue::updateOrCreate(
                    [
                        'subscription_id' => $planId,
                        'feature_id' => $featureId,
                    ],
                    ['value' => $value]
                );
            }
        }

        return back()->with('success', 'Feature matrix updated!');
    }

}

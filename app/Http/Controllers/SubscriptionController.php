<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function index(): Response
    {
        $subscriptions = Subscription::paginate(10);
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
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'features' => 'nullable|array',
        ]);
        Subscription::create($validated);
        return redirect()->route('subscriptions.index')->with('success', 'Subscription plan created.');
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
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'features' => 'nullable|array',
        ]);
        $subscription->update($validated);
        return redirect()->route('subscriptions.index')->with('success', 'Subscription plan updated.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index')->with('success', 'Subscription plan deleted.');
    }
}

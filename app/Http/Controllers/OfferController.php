<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feature;
use App\Models\Offer;
use App\Models\Store;
use App\Models\SubscriptionFeatureValue;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\OfferRequest;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $isInitialLoad = !$request->hasAny(['store_id', 'status', 'is_featured', 'is_exclusive', 'search']);

        $company = auth()->user()->company()->with('latestSubscription.subscription')->first();

        $offerLimitRaw = $company->latestSubscription->subscription->getFeatureValue('deals_limit');
        $offerLimit = (
            $offerLimitRaw === 'unlimited' ||
            $offerLimitRaw === null ||
            (is_numeric($offerLimitRaw) && (int)$offerLimitRaw === -1)
        ) ? null : (int) $offerLimitRaw;

        $query = Offer::with('store')
            ->where('company_id', $company->id);

        $query->when($request->filled('store_id'), fn ($q) => $q->where('store_id', $request->store_id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('is_featured'), fn ($q) => $q->where('is_featured', $request->is_featured))
            ->when($request->filled('is_exclusive'), fn ($q) => $q->where('is_exclusive', $request->is_exclusive))
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'));

        $limitedIds = $offerLimit
            ? $query->clone()->take($offerLimit)->pluck('id')
            : $query->clone()->pluck('id');

        $offers = Offer::with('store')
            ->whereIn('id', $limitedIds)
            ->paginate(10)
            ->withQueryString();

        $stores = Store::select('id', 'name')
            ->where('company_id', $company->id)->get();

        $categories = Category::select('id', 'name')
            ->where('company_id', $company->id)->get();

        return Inertia::render('offers/index', [
            'offers' => $offers,
            'stores' => $stores,
            'categories' => $categories,
            'filters' => $isInitialLoad
                ? [
                    'store_id' => null,
                    'status' => null,
                    'is_featured' => null,
                    'is_exclusive' => null,
                    'search' => '',
                ]
                : $request->only(['store_id', 'status', 'is_featured', 'is_exclusive', 'search']),
            'can' => [
                'create' => auth()->user()->can('create offers'),
                'edit' => auth()->user()->can('edit offers'),
                'delete' => auth()->user()->can('delete offers'),
            ],
        ]);
    }


    public function store(OfferRequest $request)
    {
        Offer::create([
            ...$request->validated(),
            'company_id' => auth()->user()->company_id
        ]);

        return redirect()->back()->with('success', 'Offer created successfully');
    }

    public function update(OfferRequest $request, Offer $offer)
    {
        $offer->update($request->validated());

        return redirect()->back()->with('success', 'Offer updated successfully');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->back()->with('success', 'Offer deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        Offer::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->delete();

        return back()->with('success', 'Offers deleted');
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');

        Offer::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['status' => $status]);

        return back()->with('success', 'Status updated');
    }

    public function bulkFeatured(Request $request)
    {
        Offer::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $request->ids)
            ->update([
                'is_featured' => $request->status,
            ]);

        return redirect()->back()->with('success', 'Offers updated as featured.');
    }

    public function bulkExclusive(Request $request)
    {
        Offer::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $request->ids)
            ->update([
                'is_exclusive' => $request->status,
            ]);

        return redirect()->back()->with('success', 'Offers updated as exclusive.');
    }

}

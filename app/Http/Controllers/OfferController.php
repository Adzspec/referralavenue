<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\OfferRequest;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::with('store') // eager load store
        ->where('company_id', auth()->user()->company_id)
            ->paginate(10)
            ->withQueryString();
        return Inertia::render('offers/index', [
            'offers' => $offers,
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
        Offer::whereIn('id', $ids)->delete();

        return back()->with('success', 'Categories deleted');
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');

        Offer::whereIn('id', $ids)->update(['status' => $status]);

        return back()->with('success', 'Status updated');
    }
}

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
        return Inertia::render('offers/index', [
            'offers' => Offer::where('company_id', auth()->user()->company_id)
                ->with('store') // Eager load the store relationship
                ->paginate(10)
                ->withQueryString(),
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
}

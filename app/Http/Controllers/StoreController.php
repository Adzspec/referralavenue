<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreRequest;

class StoreController extends Controller
{
    public function index()
    {
        return Inertia::render('stores/index', [
            'stores' => Store::where('company_id', auth()->user()->company_id)
                ->paginate(10) // You can change 10 to any desired per-page count
                ->withQueryString(),
        ]);
    }

    public function store(StoreRequest $request)
    {
        Store::create([
            ...$request->validated(),
            'company_id' => auth()->user()->company_id
        ]);

        return redirect()->back()->with('success', 'Store created successfully');
    }

    public function update(StoreRequest $request, Store $store)
    {
        $store->update($request->validated());

        return redirect()->back()->with('success', 'Store updated successfully');
    }

    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->back()->with('success', 'Store deleted successfully');
    }
}

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
        $stores = Store::where('company_id', auth()->user()->company_id)->paginate(10)->withQueryString();
        return Inertia::render('stores/index', [
            'stores' => $stores,
            'can' => [
                'create' => auth()->user()->can('create stores'),
                'edit' => auth()->user()->can('edit stores'),
                'delete' => auth()->user()->can('delete stores'),
            ],
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

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        Store::whereIn('id', $ids)->delete();

        return back()->with('success', 'Categories deleted');
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');

        Store::whereIn('id', $ids)->update(['status' => $status]);

        return back()->with('success', 'Status updated');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
//        $categories = Category::with('children')->where('company_id', auth()->user()->company_id)->whereNull('parent_id')->get();
//
//        return Inertia::render('categories/index', [
//            'categories' => $categories
//        ]);

        $categories = Category::where('company_id', auth()->user()->company_id)->paginate(10)->withQueryString();
        return Inertia::render('categories/index', [
            'categories' => $categories,
            'can' => [
                'create' => auth()->user()->can('create categories'),
                'edit' => auth()->user()->can('edit categories'),
                'delete' => auth()->user()->can('delete categories'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        Category::create([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'],
            'company_id' => auth()->user()->company_id
        ]);

        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category)
    {
//        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category->update($validated);

        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
//        $this->authorize('delete', $category);
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        Category::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->delete();

        return back()->with('success', 'Categories deleted');
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');

        Category::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['status' => $status]);

        return back()->with('success', 'Status updated');
    }

}

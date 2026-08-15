<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplaintCategory;
use Illuminate\Http\Request;

class ComplaintCategoryController extends Controller
{
    public function index()
    {

        $categories = ComplaintCategory::withCount('complaints')
            ->orderByDesc('complaints_count')
            ->get();

        return view('admin.complaints.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|max:100|unique:complaint_categories,name',
        ]);

        ComplaintCategory::create([
            'name' => $validated['category_name'],
        ]);

        return redirect()
            ->route('admin.categories')
            ->with('success', 'Category has been added!');
    }

    public function update(Request $request, ComplaintCategory $category)
    {

        $validated = $request->validate([
            'name' => 'required|max:100|unique:complaint_categories,name',
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.categories')
            ->with('success', 'Category has been updated!');
    }

    public function destroy(ComplaintCategory $category)
    {

        if ($category->complaints()->exists()) {
            return back()->with('error', 'Cannot delete a category that has complaints assigned to it.');
        }

        $category->delete($category);

        return redirect()
            ->route('admin.categories')
            ->with('success', 'Category deleted successfully.');
    }
}

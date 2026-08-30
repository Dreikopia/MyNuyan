<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplaintCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ComplaintCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = QueryBuilder::for(
            ComplaintCategory::query()
                ->where('is_archived', false)
                ->withCount('complaints')
        )
            ->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%");
                    });
                }),
            )
            ->defaultSort('-created_at')
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('admin.complaints.categories._table', [
                    'categories' => $categories,
                ])->render(),
            ]);
        }

        return view('admin.complaints.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function archived(Request $request)
    {
        $categories = QueryBuilder::for(
            ComplaintCategory::query()
                ->where('is_archived', true)
                ->withCount('complaints')
        )
            ->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%");
                    });
                }),
            )
            ->defaultSort('-created_at')
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('admin.complaints.categories._table', [
                    'categories' => $categories,
                    'archivedView' => true,
                ])->render(),
            ]);
        }

        return view('admin.complaints.categories.archived', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|max:100|unique:complaint_categories,name',
            'description' => 'nullable|max:100',
        ]);

        ComplaintCategory::create([
            'name' => $validated['category_name'],
            'description' => $validated['description'],
        ]);

        return redirect()
            ->route('admin.categories')
            ->with('success', 'Category has been added!');
    }

    public function update(Request $request, ComplaintCategory $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:100',
                Rule::unique('complaint_categories', 'name')
                    ->ignore($category->id),
            ],
            'description' => 'nullable|string|max:500',
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

        $category->delete();

        return redirect()
            ->route('admin.categories')
            ->with('success', 'Category deleted successfully.');
    }

    public function archive($id)
    {
        $complaint = ComplaintCategory::findOrFail($id);
        $complaint->is_archived = true;
        $complaint->save();

        return redirect()->back()->with('success', 'Complaint archived');
    }

    public function unArchive($id)
    {
        $complaint = ComplaintCategory::findOrFail($id);
        $complaint->is_archived = false;
        $complaint->save();

        return redirect()->back()->with('success', 'Complaint category Unarchived');
    }
}

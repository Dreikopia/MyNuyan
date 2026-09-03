<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintCategory;
use App\Http\Requests\UpdateComplaintCategory;
use App\Models\ComplaintCategory;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ComplaintCategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
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
            ->paginate($perPage)
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
            ->paginate()
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

    public function store(StoreComplaintCategory $request)
    {
        $validated = $request->validated();

        ComplaintCategory::create([
            'name' => $validated['category_name'],
            'description' => $validated['description'],
        ]);

        return redirect()
            ->route('admin.categories')
            ->with('success', 'Category has been added!');
    }

    public function update(UpdateComplaintCategory $request, ComplaintCategory $category)
    {
        $validated = $request->validated();

        $validated['default_priority'] ??= $category->default_priority->value;

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

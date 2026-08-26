<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\UpdateComplaintStatus;
use App\Enums\ComplaintPriority;
use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $complaints = QueryBuilder::for(Complaint::active())
            ->allowedFilters(
                AllowedFilter::exact('status'),
                AllowedFilter::exact('complaint_category_id'),
                AllowedFilter::exact('priority'),

                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('description', 'like', "%{$value}%")
                            ->orWhere('location', 'like', "%{$value}%")
                            ->orWhereHas('user', function ($q) use ($value) {
                                $q->where('first_name', 'like', "%{$value}%")
                                    ->orWhere('last_name', 'like', "%{$value}%");
                            })
                            ->orWhereHas('category', function ($q) use ($value) {
                                $q->where('name', 'like', "%{$value}%");
                            });
                    });
                }),
            )
            ->with(['category', 'user', 'images'])
            ->defaultSort('-created_at')
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('admin.complaints._table', [
                    'complaints' => $complaints,
                ])->render(),
            ]);
        }

        $categories = ComplaintCategory::all();

        $selectedCategory = $request->input('filter.complaint_category_id');

        return view('admin.complaints.index', [
            'complaints' => $complaints,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'statusCounts' => Complaint::allStatusCounts($selectedCategory),
        ]);
    }

    public function archived(Request $request)
    {
        $complaints = QueryBuilder::for(Complaint::archived())
            ->allowedFilters(
                AllowedFilter::exact('status'),
                AllowedFilter::exact('complaint_category_id'),
                AllowedFilter::exact('priority'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('description', 'like', "%{$value}%")
                            ->orWhere('location', 'like', "%{$value}%")
                            ->orWhereHas('user', fn ($q) => $q->where('first_name', 'like', "%{$value}%")
                                ->orWhere('last_name', 'like', "%{$value}%"))
                            ->orWhereHas('category', fn ($q) => $q->where('name', 'like', "%{$value}%"));
                    });
                }),
            )
            ->with(['category', 'user', 'images'])
            ->defaultSort('-created_at')
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('admin.complaints._table', [
                    'complaints' => $complaints,
                    'archivedView' => true,
                ])->render(),
            ]);
        }

        return view('admin.complaints.archived', [
            'complaints' => $complaints,
            'archivedView' => true,
        ]);
    }

    public function update(Request $request, Complaint $complaint, UpdateComplaintStatus $action)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::enum(ComplaintStatus::class)],
            'priority' => ['nullable', Rule::enum(ComplaintPriority::class)],
            'remarks' => ['nullable', 'string'],
        ]);
        // so my priority will work even no status is checked
        $validated['status'] ??= $complaint->status->value;
        $validated['priority'] ??= $complaint->priority->value;

        try {
            $action->handle($complaint, $validated);
        } catch (DomainException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return back()->with('success', 'Complaint updated successfully.');
    }

    public function archive($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->is_archived = true;
        $complaint->save();

        return redirect()->back()->with('success', 'Complaint archived');
    }

    public function unArchive($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->is_archived = false;
        $complaint->save();

        return redirect()->back()->with('success', 'Complaint Unarchive.');
    }
}

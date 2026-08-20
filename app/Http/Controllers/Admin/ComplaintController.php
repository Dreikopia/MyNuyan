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

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        if (! in_array($status, ComplaintStatus::values())) {
            $status = null;
        }

        $categoryId = $request->category;
        $search = $request->search;

        $complaints = Complaint::with(['category', 'user', 'images'])
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->when($categoryId, fn ($query, $categoryId) => $query->where('complaint_category_id', $categoryId))
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('category', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $categories = ComplaintCategory::all();

        return view('admin.complaints.index', [
            'complaints' => $complaints,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'statusCounts' => Complaint::allStatusCounts($categoryId),
        ]);
    }

    public function update(Request $request, Complaint $complaint, UpdateComplaintStatus $action)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::enum(ComplaintStatus::class)],
            'priority' => ['required', Rule::enum(ComplaintPriority::class)],
            'remarks' => ['nullable', 'string'],
        ]);
        // so my priority will work even no status is checked
        $validated['status'] ??= $complaint->status->value;

        try {
            $action->handle($complaint, $validated);
        } catch (DomainException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return back()->with('success', 'Complaint status updated successfully.');
    }
}

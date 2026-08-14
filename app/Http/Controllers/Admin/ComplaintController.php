<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
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

        $complaints = Complaint::with(['category', 'user', 'images'])
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->when($categoryId, fn ($query, $categoryId) => $query->where('complaint_category_id', $categoryId))
            ->latest()
            ->paginate(7)
            ->withQueryString();

        $categories = ComplaintCategory::all();

        return view('admin.complaints.index', [
            'complaints' => $complaints,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'statusCounts' => Complaint::allStatusCounts($categoryId),
        ]);
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(ComplaintStatus::class)],
            'remarks' => ['nullable', 'string'],
        ]);

        $currentStatus = $complaint->status;               // e.g. SUBMITTED
        $newStatus = ComplaintStatus::from($validated['status']); // e.g. UNDER_REVIEW

        // Step 2: Ask the enum — is this move even allowed?
        if (! $currentStatus->canTransitionTo($newStatus)) {
            return back()->withErrors([
                'status' => 'That status change is not allowed.',
            ]);
        }
        // Step 3: Only now do we actually save
        $complaint->update([
            'status' => $newStatus,
        ]);

        $complaint->statusHistories()->create([
            'changed_by' => auth('admin')->id(),
            'status' => $newStatus->value,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return back()->with('success', 'Complaint status updated successfully.');
    }
}

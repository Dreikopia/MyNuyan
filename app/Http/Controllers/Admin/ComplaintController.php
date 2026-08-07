<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->status;

        if (! in_array($status, ComplaintStatus::values())) {
            $status = null;
        }

        $categoryId = $request->category;

        $complaints = Complaint::with(['category', 'user'])
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
            'status' => ['required', Rule::in(ComplaintStatus::values())],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $complaint->update($validated);

        return back()->with('success', 'Complaint updated successfully.');
    }
}

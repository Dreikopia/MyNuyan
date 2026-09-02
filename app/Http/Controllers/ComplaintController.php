<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreComplaint;
use App\Enums\ComplaintStatus;
use App\Http\Requests\StoreComplaintRequest;
use App\Models\Admin;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Notifications\ComplaintSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $status = $request->status;

        if (! in_array($status, ComplaintStatus::values())) {
            $status = null;
        }

        $complaints = $user->complaints()
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        return view('resident.complaints.index', [
            'complaints' => $complaints,
            'statusCounts' => Complaint::statusCounts($user),
        ]);
    }

    public function show(Complaint $complaint)
    {
        Gate::authorize('view', $complaint);

        $complaint->load('images', 'category');

        return view('resident.complaints.show', [
            'complaint' => $complaint,
        ]);
    }

    public function createCategory()
    {
        $categories = ComplaintCategory::all();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Select Category', 'url' => null],
        ];

        return view('resident.complaints.create.category', ['categories' => $categories, 'breadcrumbs' => $breadcrumbs]);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'complaint_category_id' => 'required|exists:complaint_categories,id',
        ]);

        session(['complaint.category_id' => $validated['complaint_category_id']]);

        return redirect()->route('complaints.create.details');
    }

    public function createDetails()
    {
        abort_unless(
            session()->has('complaint.category_id'),
            302,
            redirect()->route('complaints.create.category')
        );

        $category = ComplaintCategory::findOrFail(session('complaint.category_id'));

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Select Category', 'url' => route('complaints.create.category')],
            ['label' => $category->name, 'url' => null],
        ];

        return view('resident.complaints.create.details', ['category' => $category, 'breadcrumbs' => $breadcrumbs]);
    }

    public function store(StoreComplaintRequest $request, StoreComplaint $action)
    {
        $categoryId = session('complaint.category_id');
        abort_unless($categoryId, 400, 'No category selected.');

        $complaint = $action->handle([
            ...$request->validated(),
            'complaint_category_id' => $categoryId,
        ]);

        $admins = Admin::all();

        foreach ($admins as $admin) {
            $admin->notify(new ComplaintSubmitted($complaint));
        }

        session()->forget('complaint.category_id');

        return redirect()
            ->route('complaint.index')->with('success', 'Complaint submitted!');
    }
}

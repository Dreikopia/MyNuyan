<?php

namespace App\Http\Controllers;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $complaint->load('images', 'category');

        return view('resident.complaints.show', [
            'complaint' => $complaint,
        ]);
    }

    /**
     * STEP 1 — Show category selection
     */
    public function createCategory()
    {
        $categories = ComplaintCategory::all();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Select Category', 'url' => null],
        ];

        return view('resident.complaints.create.category', compact('categories', 'breadcrumbs'));
    }

    /**
     * STEP 1 — Store chosen category in session, move to step 2
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'complaint_category_id' => 'required|exists:complaint_categories,id',
        ]);

        session(['complaint.category_id' => $validated['complaint_category_id']]);

        return redirect()->route('complaints.create.details');
    }

    /**
     * STEP 2 — Show location/description/images form
     */
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

        return view('resident.complaints.create.details', compact('category', 'breadcrumbs'));
    }

    /**
     * STEP 2 — Final submission, persist the complaint
     */
    public function store(Request $request)
    {
        $categoryId = session('complaint.category_id');
        abort_unless($categoryId, 400, 'No category selected.');

        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $complaint = Auth::user()->complaints()->create([
            'complaint_category_id' => $categoryId,
            'location' => $validated['location'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('complaints', 'public');

                ComplaintImage::create([
                    'complaint_id' => $complaint->id,
                    'image_path' => $path,
                ]);
            }
        }
        session()->forget('complaint.category_id');

        return redirect()
            ->route('home')
            ->with('success', 'Complaint submitted!');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotline;
use App\Models\HotlineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotlineController extends Controller
{
    public function index()
    {
        $hotlines = Hotline::with(['category', 'numbers'])
            ->orderBy('name')
            ->get();

        $categories = HotlineCategory::orderBy('name')->get();

        return view('admin.hotlines.index', [
            'hotlines' => $hotlines,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotline_category_id' => 'required|exists:hotline_categories,id',
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:50',
        ]);

        DB::transaction(function () use ($validated) {
            $hotline = Hotline::create([
                'hotline_category_id' => $validated['hotline_category_id'],
                'name' => $validated['name'],
                'location' => $validated['location'] ?? null,
                'status' => 'active',
            ]);

            $hotline->numbers()->create([
                'number' => $validated['contact_number'],
                'type' => 'mobile',
                'is_primary' => true,
            ]);
        });

        return redirect()
            ->route('admin.hotlines.index')
            ->with('success', 'Hotline added.');
    }

    public function update(Request $request, Hotline $hotline)
    {
        $validated = $request->validate([
            'hotline_category_id' => 'required|exists:hotline_categories,id',
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
        ]);

        $hotline->update($validated);

        return redirect()
            ->route('admin.hotlines.index')
            ->with('success', 'Hotline updated.');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotline;
use App\Models\HotlineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotlineController extends Controller
{
    public function index()
    {
        $hotlines = Hotline::all();
        $categories = HotlineCategory::all();

        return view('admin.hotlines.index', [
            'hotlines' => $hotlines,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotline_category_id' => 'required|exists:hotline_categories,id',
            'name' => 'required|max:100',
            'phone_number' => 'required|string|max:20',
        ]);

        // store
        $hotline = Auth::guard('admin')
            ->user()
            ->hotlines()
            ->create($validated);

        return redirect()->route('admin.hotlines.index', [
            'hotlines' => $hotline,
        ]);
    }
}

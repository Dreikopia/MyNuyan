<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::query()
            ->withCount('news')
            ->latest()
            ->paginate(10);

        return view('admin.news.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        NewsCategory::create([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.news.categories')
            ->with('success', 'Category has been added!');
    }
}

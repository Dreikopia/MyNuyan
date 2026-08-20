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
        return view('admin.news.categories.index', [
            'categories' => NewsCategory::all(),
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

        return redirect()->route('admin.news.categories');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index()
    {
        return view('admin.news.index', [
            'categories' => NewsCategory::all(),
            'news' => News::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'category' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        Auth::guard('admin')->user()->news()->create([
            'title' => $validated['title'],
            'news_category_id' => $validated['category'],
            'description' => $validated['description']]);

        return redirect()->route('admin.news');
    }
}

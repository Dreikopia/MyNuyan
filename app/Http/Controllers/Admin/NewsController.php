<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;

class NewsController extends Controller
{
    public function create()
    {
        $categories = NewsCategory::all();

        return view('admin.news.create', compact('categories'));
    }
}

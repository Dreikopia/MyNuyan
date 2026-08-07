<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;

class NewsCategoryController extends Controller
{
    public function index()
    {
        return view('admin.news.categories.index', [
            'categories' => NewsCategory::all(),
        ]);
    }
}

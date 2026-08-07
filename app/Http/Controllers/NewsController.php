<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('resident.news.index', [
            'categories' => NewsCategory::all(),
            'news' => News::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news): void
    {
        //
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = QueryBuilder::for(
            News::query()->with('category')
        )
            ->allowedFilters(...[
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('title', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%");
                    });
                }),
                AllowedFilter::exact('status'),
                AllowedFilter::callback('news_category_id', function ($query, $value) {
                    $query->where('news_category_id', $value);
                }),
            ])
            ->allowedSorts(...[
                'created_at',
                'updated_at',
            ])
            ->defaultSort('-created_at')
            ->get();

        $categories = NewsCategory::orderBy('name')->get();

        $statusCounts = [
            'all' => News::count(),
            'published' => News::where('status', 'published')->count(),
            'draft' => News::where('status', 'draft')->count(),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('admin.news.partials._grid', [
                    'news' => $news,
                ])->render(),
                'statusCounts' => $statusCounts,
            ]);
        }

        return view('admin.news.index', [
            'news' => $news,
            'categories' => $categories,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function drawer(News $post)
    {
        return view('admin.news.partials.drawer-content', [
            'post' => $post,
            'categories' => NewsCategory::all(),
        ]);
    }

    public function store(NewsRequest $request)
    {
        $validated = $request->validated();

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        Auth::guard('admin')->user()->news()->create([
            'title' => $validated['title'],
            'news_news_category_id' => $validated['category'],
            'status' => $validated['status'],
            'description' => $validated['description'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.news');
    }

    public function update(NewsRequest $request, News $post)
    {
        $validated = $request->validated();

        if ($request->boolean('remove_image') && $post->image_path) {
            Storage::disk('public')->delete($post->image_path);
            $post->image_path = null;
        }

        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }

            $post->image_path = $request->file('image')->store('news', 'public');
        }

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'news_category_id' => $validated['category'],
        ]);

        return redirect()
            ->route('admin.news')
            ->with('success', 'News updated');
    }

    public function destroy(News $news)
    {
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }

        $news->delete($news);

        return redirect()
            ->route('admin.news')
            ->with('success', 'News deleted successfully.');
    }
}

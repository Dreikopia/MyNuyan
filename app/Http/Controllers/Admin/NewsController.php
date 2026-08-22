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

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function ($query, $category) {
                $query->where('news_category_id', $category);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->get();

        return view('admin.news.index', [
            'categories' => NewsCategory::all(),
            'news' => $news,
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
            'news_category_id' => $validated['category'],
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

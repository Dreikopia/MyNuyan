@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">News Articles</h1>
            <p class="text-sm text-muted-foreground">Manage News</p>
        </div>
        <x-admin.news-modal :categories="$categories" />

    </div>

    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-card">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($news as $article)
                    <tr>
                        <td>{{ $article->category->name ?? '' }}</td>
                        <td>{{ $article->description }}</td>
                        <td>{{ $article->status }}</td>
                        <td>{{ $article->status }}</td>
                        <td>
                            <button class="btn btn-primary">Edit</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

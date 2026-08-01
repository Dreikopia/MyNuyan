@extends('layouts.admin')

@section('content')
    Hello
    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" class="input input-bordered w-full" placeholder="Title" required>

        <select name="type" class="select select-bordered w-full">
            <option value="news">News</option>
            <option value="announcement">Announcement</option>
        </select>

        <select name="news_category_id" class="select select-bordered w-full">
            <option value="">-- No category --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <input type="date" name="date" class="input input-bordered w-full">
        <textarea name="description" class="textarea textarea-bordered w-full" required></textarea>
        <input type="file" name="image_path" class="file-input file-input-bordered w-full">

        <button type="submit" class="btn btn-primary">Post</button>
    </form>
@endsection

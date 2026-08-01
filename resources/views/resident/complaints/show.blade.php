@extends('layouts.resident')

@section('content')
    <div class="fixed top-0 left-0 right-0 h-12 bg-gray-500">
        <h1 class="text-xl font-bold">Report details</h1>
    </div>
    <div class="card bg-base-100 w-full shadow-sm">
        <figure>
            @forelse ($complaint->images as $img)
                <img src="{{ asset('storage/' . $img->image_path) }}">
            @empty
                <p class="text-gray-400 text-sm">No images attached</p>
            @endforelse
        </figure>
        <div class="card-body">
            <h2 class="card-title">{{ $complaint->category->name }}</h2>
            <p>{{ $complaint->description }}</p>
            <p>{{ $complaint->created_at }}</p>
            <p>{{ $complaint->location }}</p>
        </div>
    </div>

    <div class="card w-full bg-base-100 card-lg shadow-sm">
        <div class="card-body">
            <h2 class="card-title">Report Timeline</h2>

            <p>{{ $complaint->created_at->diffForHumans() }}</p>
            <p>{{ $complaint->status }}</p>
            <div class="justify-end">
                <p class="font-bold">Remarks</p>
            </div>
        </div>
    </div>
@endsection

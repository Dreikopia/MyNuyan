@extends('layouts.resident')


@section('content')
    <div class="flex justify-between px-2 py-4">
        <div>
            <h1 class="text-xl font-bold font-kopub">News & Updates</h1>
            <p class="text-muted-foreground text-sm font-kopub">Stay updated with the latest barangay news</p>
        </div>
        <div>
            <x-icons.notification />
        </div>
    </div>



    <div class="mb-2">
        <h2 class="text-lg font-bold">Featured News</h2>
    </div>

    <div class="space-y-5">
        @forelse($news as $post)
            <div class="card bg-base-300 w-full shadow-sm">
                @if ($post->image_path)
                    <figure>
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                            class="object-cover w-full h-48" />
                    </figure>
                @endif

                <div class="card-body">
                    <div class="text-xl font-bold text-primary">
                        {{ $post->category->name }}
                    </div>

                    <h2 class="card-title">
                        {{ $post->title }}
                    </h2>

                    <p>
                        {{ $post->description }}
                    </p>
                </div>
            </div>
        @empty
            <p class="text-base-content/60">No news available.</p>
        @endforelse
    </div>

    <x-resident.bottom-nav />
@endsection

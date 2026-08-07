@extends('layouts.resident')


@section('content')
    <div class="flex justify-between px-2 py-4">
        <div>
            <h1 class="text-xl font-bold">News & Updates</h1>
            <p class="text-muted-foreground text-sm">Stay updated with the latest barangay news</p>
        </div>
        <div>
            <x-icons.notification />
        </div>
    </div>



    <div class="mb-2">
        <h2 class="text-lg font-bold">Featured News</h2>
    </div>

    @forelse($news as $article)
        <div class="card bg-base-300 w-full shadow-sm">
            <figure>
                {{-- <img src="/images/medical-mission.webp" class="object-cover w-full" /> --}}
            </figure>
            <div class="card-body">
                <h2 class="text-xl font-bold text-primary">{{ $article->category->name }}</h2>
                <h2 class="card-title">{{ $article->title }}</h2>
                <h2 class="card-title">{{ $article->description }}</h2>
            </div>
        </div>
    @empty
        No News Nigga
    @endforelse

    <x-resident.bottom-nav />
@endsection

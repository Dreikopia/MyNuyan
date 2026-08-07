@extends('layouts.resident')

@section('content')
    <div class="flex gap-x-5 items-center">
        <a href="{{ route('home') }}">
            <x-icons.back />
        </a>
        <h1 class="text-xl font-bold mt-4 mb-4">
            Report details
        </h1>
    </div>


    <div class="card bg-surface w-full shadow-sm rounded-xl overflow-hidden">
        @if ($complaint->images->isNotEmpty())
            <figure class="grid grid-cols-2 gap-1 bg-muted">
                @foreach ($complaint->images->take(4) as $index => $img)
                    <div class="relative {{ $complaint->images->count() === 1 ? 'col-span-2' : '' }}">
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-40 object-cover"
                            alt="Complaint attachment">

                        @if ($index === 3 && $complaint->images->count() > 4)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <span class="text-white font-koho font-bold text-lg">
                                    +{{ $complaint->images->count() - 4 }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </figure>
        @endif

        <div class="card-body gap-3">
            <div class="flex items-start justify-between gap-2">
                <h2 class="card-title font-koho">{{ $complaint->category->name }}</h2>

                @if ($complaint->status)
                    <span class="badge badge-primary badge-sm rounded-full whitespace-nowrap">
                        {{ $complaint->status->label() }}
                    </span>
                @endif
            </div>

            <p class="text-sm text-muted-foreground leading-relaxed">
                {{ $complaint->description }}
            </p>

            <div class="flex flex-col gap-1.5 pt-2 border-t border-border">
                <div class="flex items-center gap-2 text-xs text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ $complaint->created_at->format('M d, Y \a\t g:i A') }}</span>
                </div>

                <div class="flex items-center gap-2 text-xs text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ $complaint->location }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection

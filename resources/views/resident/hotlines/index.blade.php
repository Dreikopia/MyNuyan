@extends('layouts.resident')


@section('content')
    <h1 class="text-sm font-bold">List of hotlines</h1>
    <div class="space-y-5">
        @forelse($hotlines as $hotline)
            <div class="card bg-base-300 w-full shadow-sm">

                <div class="card-body">
                    <div class="text-xl font-bold text-primary">
                        {{ $hotline->category->name }}
                    </div>

                    <h2 class="card-title">
                        {{ $hotline->phone_number }}
                    </h2>

                    <p>
                        {{ $hotline->description }}
                    </p>
                </div>
            </div>
        @empty
            <p class="text-base-content/60">No news available.</p>
        @endforelse
    </div>
    <x-resident.bottom-nav />
@endsection

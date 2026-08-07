@extends('layouts.resident')


@section('content')
    <div class="flex items-center gap-15">
        <a href="{{ route('home') }}">
            <x-icons.back />
        </a>
        <h1 class="text-xl font-bold mt-4 mb-4">Your Reports</h1>
    </div>

    <div class="flex gap-2 mb-4 overflow-x-scroll">
        <a href="{{ route('complaint.index') }}" class="btn btn-outline rounded-full">
            All
        </a>
        @foreach (App\Enums\ComplaintStatus::cases() as $status)
            <a href="/complaints?status={{ $status->value }}"
                class="btn {{ request('status') === $status->value ? '' : 'btn btn-outline rounded-full bg-base-200' }}">
                {{ $status->label() }}
                <span class="pl-3 text-xs">
                    {{ $statusCounts->get($status->value) }}
                </span>
            </a>
        @endforeach
    </div>
    <div class="p-4 space-y-4">
        @forelse ($complaints as $complaint)
            <x-resident.complaint-card :complaint="$complaint" />
        @empty
            <h1 class="text-2xl text-center">No reports found</h1>
        @endforelse

    </div>
@endsection

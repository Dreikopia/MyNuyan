@extends('layouts.admin')
@php
    $currentLabel = request('status') ? App\Enums\ComplaintStatus::from(request('status'))->label() : 'All';
@endphp

@section('content')
    <form method="GET" action="{{ route('admin.complaints') }}" id="filter-form">

        <div class="flex items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold">Complaints</h1>
                <p class="text-sm text-muted-foreground">Manage Complaints</p>
            </div>
        </div>

        <div class="flex items-center gap-3 mb-4">

            {{-- Search --}}
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search complaints..."
                class="input bg-transparent input-sm w-full" onchange="this.form.submit()">

            {{-- Status --}}
            <div class="dropdown">
                <div tabindex="0" role="button"
                    class="btn btn-sm btn-outline border-base-300 font-normal justify-between w-30">
                    <span>Status: {{ $currentLabel }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-10 w-40 p-2 shadow-sm">
                    <li>
                        <a href="{{ route('admin.complaints', ['category' => $selectedCategory]) }}"
                            class="{{ !request('status') ? 'active' : '' }}">
                            All
                            <span class="opacity-70">{{ $statusCounts->get('all') }}</span>
                        </a>
                    </li>

                    @foreach (App\Enums\ComplaintStatus::cases() as $status)
                        <li>
                            <a href="{{ route('admin.complaints', [
                                'status' => $status->value,
                                'category' => $selectedCategory,
                            ]) }}"
                                class="{{ request('status') === $status->value ? 'active' : '' }}">
                                {{ $status->label() }}
                                <span class="opacity-70">{{ $statusCounts->get($status->value) }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Category --}}
            <select name="category" class="select select-bordered select-sm w-44" onchange="this.form.submit()">
                <option value="">Category: All</option>

                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (string) $selectedCategory === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

        </div>

    </form>
    <x-admin.complaints-table :complaints="$complaints" :selected-category="$selectedCategory" :status-counts="$statusCounts" :categories="$categories" />
@endsection

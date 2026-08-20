@extends('layouts.admin')
@php
    $currentLabel = request('status') ? App\Enums\ComplaintStatus::from(request('status'))->label() : 'All';
@endphp

@section('content')
    <form method="GET" action="{{ route('admin.complaints') }}" id="filter-form">
        <x-admin.header title="Complaints" description="Manage Complaints">
            <div class="flex items-center gap-3">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search complaints..."
                    class="input input-sm bg-transparent w-64" onchange="this.form.submit()">

                <div class="dropdown">
                    <div tabindex="0" role="button"
                        class="btn btn-sm btn-outline border-base-300 font-normal justify-between w-32">
                        <span>Status: {{ $currentLabel }}</span>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-10 w-45 p-2 shadow-sm">
                        <li>
                            <a href="{{ route('admin.complaints', [
                                'category' => $selectedCategory,
                                'search' => request('search'),
                            ]) }}"
                                class="{{ !request('status') ? 'active' : '' }}">
                                All
                                <span class="opacity-70">
                                    {{ $statusCounts->get('all') }}
                                </span>
                            </a>
                        </li>

                        @foreach (App\Enums\ComplaintStatus::cases() as $status)
                            <li>
                                <a href="{{ route('admin.complaints', [
                                    'status' => $status->value,
                                    'category' => $selectedCategory,
                                    'search' => request('search'),
                                    'priority' => request('priority'),
                                ]) }}"
                                    class="{{ request('status') === $status->value ? 'active' : '' }}">
                                    {{ $status->label() }}

                                    <span class="opacity-70">
                                        {{ $statusCounts->get($status->value) }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <select name="category" class="select select-bordered select-sm w-40" onchange="this.form.submit()">
                    <option value="">Category: All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ (string) $selectedCategory === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="priority" class="select select-bordered select-sm w-40" onchange="this.form.submit()">
                    <option value="">Priority: All</option>

                    @foreach (App\Enums\ComplaintPriority::cases() as $priority)
                        <option value="{{ $priority->value }}"
                            {{ request('priority') === $priority->value ? 'selected' : '' }}>
                            {{ $priority->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
        </x-admin.header>
    </form>

    <x-admin.complaints-table :complaints="$complaints" :selected-category="$selectedCategory" :status-counts="$statusCounts" :categories="$categories" />

    <div class="flex justify-end mt-6">

        <div class="join">
            @if ($complaints->onFirstPage())
                <button class="join-item btn btn-sm btn-outline" disabled>
                    Previous
                </button>
            @else
                <a href="{{ $complaints->previousPageUrl() }}" class="join-item btn btn-sm btn-outline">
                    Previous
                </a>
            @endif


            {{-- Page Numbers --}}
            @foreach ($complaints->getUrlRange(1, $complaints->lastPage()) as $page => $url)
                <a href="{{ $url }}"
                    class="join-item btn btn-sm
                    {{ $page == $complaints->currentPage() ? 'btn-active' : '' }}">
                    {{ $page }}
                </a>
            @endforeach


            {{-- Next --}}
            @if ($complaints->hasMorePages())
                <a href="{{ $complaints->nextPageUrl() }}" class="join-item btn btn-sm btn-outline">
                    Next
                </a>
            @else
                <button class="join-item btn btn-sm btn-outline" disabled>
                    Next
                </button>
            @endif

        </div>

    </div>
@endsection

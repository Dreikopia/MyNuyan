@extends('layouts.admin')

@section('content')
    <form method="GET" action="{{ route('admin.complaints') }}" id="filter-form">

        {{-- Search --}}
        <div class="flex items-center mb-4">

            <div>
                <h1 class="text-2xl font-bold">
                    Complaints
                </h1>

                <p class="text-sm text-muted-foreground">
                    Manage Complaints
                </p>
            </div>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search complaints…"
                class="input input-bordered input-sm w-56 ml-auto" />
        </div>


        {{-- Status + Category --}}
        <div class="flex items-center justify-between gap-3 mb-4">

            {{-- Status filters --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1">

                <a href="{{ route('admin.complaints', [
                    'category' => $selectedCategory,
                ]) }}"
                    class="btn btn-sm shrink-0 text-xs
                    {{ !request('status') ? 'btn-primary' : 'btn-ghost bg-base-200' }}">
                    All

                    <span class="opacity-70">
                        {{ $statusCounts->get('all') }}
                    </span>
                </a>

                @foreach (App\Enums\ComplaintStatus::cases() as $status)
                    <a href="{{ route('admin.complaints', [
                        'status' => $status->value,
                        'category' => $selectedCategory,
                    ]) }}"
                        class="btn btn-sm shrink-0 text-xs
                        {{ request('status') === $status->value ? 'btn-primary' : 'btn-ghost bg-base-200' }}">
                        {{ $status->label() }}

                        <span class="opacity-70">
                            {{ $statusCounts->get($status->value) }}
                        </span>
                    </a>
                @endforeach

            </div>

            {{-- Category --}}
            <select name="category" class="select select-bordered select-sm w-40 shrink-0" onchange="this.form.submit()">
                <option value="">
                    All Categories
                </option>

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

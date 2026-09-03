@extends('layouts.admin')

@section('content')
    <x-admin.header title="Complaints" :title-url="route('admin.complaints')" :breadcrumbs="[['label' => 'Categories']]">
        <x-modal id="create-category" name="New Category" class="btn btn-primary">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="flex flex-col space-y-2">
                    <div>
                        <x-field name="category_name" label="Category name" placeholder="Name or type of the category" />
                        <x-field name="description" type="textarea" label="Description"
                            placeholder="What best describe this category" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">
                            Add
                        </button>
                    </div>
                </div>
            </form>
        </x-modal>
    </x-admin.header>


    <div x-data="{ search: @js(request('filter.search', '')) }" class="flex flex-col">
        <div class="flex items-end justify-between gap-2 pb-3 flex-wrap shrink-0">
            <div>
                <label class="flex items-center gap-2 bg-base-200 rounded-sm px-3 py-1.5 w-56">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-60" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path stroke-linecap="round" d="m21 21-4.3-4.3" />
                    </svg>

                    <input type="text" x-model="search"
                        x-on:keydown.enter.prevent="window.location.href = updateQueryParam(window.location.href, 'filter[search]', search)"
                        autocomplete="off"
                        class="bg-transparent border-none outline-none text-xs w-full placeholder:text-base-content/50"
                        placeholder="Search categories">
                </label>
            </div>

            <a href="{{ route('admin.categories.archived') }}" class="btn btn-sm btn-outline shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="4" rx="1" />
                    <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8" />
                    <path stroke-linecap="round" d="M10 12h4" />
                </svg>
                View Archived
            </a>
        </div>

        <div class="h-[470px] overflow-y-auto rounded-md border border-base-300 scrollbar-thin">
            <div id="complaint-categories-table">
                @include('admin.complaints.categories._table')
            </div>
        </div>

        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center gap-1 text-[11px]">
                <span class="text-base-content/60">Rows:</span>

                <select class="select select-xs select-bordered"
                    onchange="window.location.href = updateQueryParam(window.location.href, 'per_page', this.value)">
                    @foreach ([10, 25, 50] as $option)
                        <option value="{{ $option }}" @selected(request('per_page', 10) == $option)>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- MIDDLE: count --}}
            <span class="text-[11px] text-base-content/60">
                @if ($categories->total() > 0)
                    {{ $categories->firstItem() }}-{{ $categories->lastItem() }}
                    of {{ $categories->total() }}
                @else
                    0 results
                @endif
            </span>

            {{-- RIGHT: pagination --}}
            <div class="join">
                <a href="{{ $categories->previousPageUrl() ?? '#' }}"
                    class="join-item btn btn-xs btn-outline {{ $categories->onFirstPage() ? 'btn-disabled' : '' }}">
                    Previous page
                </a>

                <a href="{{ $categories->nextPageUrl() ?? '#' }}"
                    class="join-item btn btn-xs btn-outline {{ !$categories->hasMorePages() ? 'btn-disabled' : '' }}">
                    Next
                </a>
            </div>
        </div>
        <script>
            function updateQueryParam(url, key, value) {
                const u = new URL(url);
                u.searchParams.set(key, value);
                u.searchParams.set('page', 1);
                return u.toString();
            }
        </script>
    @endsection

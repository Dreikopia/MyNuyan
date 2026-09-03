@extends('layouts.admin')

@php
use App\Enums\ComplaintStatus;
use App\Enums\ComplaintPriority;
@endphp

@section('content')
<x-admin.header title="Complaints">

    {{-- plain <a> instead of <x-button>, styled the same way with daisyUI classes --}}
    <a href="{{ route('admin.categories') }}" class="btn btn-sm bg-primary/50 rounded-t-xl rounded-bl-xl rounded-br-none">
        Manage Categories
    </a>

    {{-- plain bell icon instead of <x-icons.notifications> --}}
    <button type="button" class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
        </svg>
    </button>

</x-admin.header>


<div x-data="{
    // ---- filter values (read from the URL on first load, so refreshing keeps your filters) ----
    search: @js(request('filter.search', '')),
    status: @js(request('filter.status', '')),
    category: @js(request('filter.complaint_category_id', '')),
    priority: @js(request('filter.priority', '')),
    sort: @js(request('sort', '-created_at')),

    // ---- pagination values ----
    perPage: @js((int) request('per_page', 10)),
    page: @js((int) request('page', 1)),

    // ---- which popover is currently open (only one at a time) ----
    statusOpen: false,
    categoryOpen: false,
    priorityOpen: false,
    sortOpen: false,
    perPageOpen: false,

    loading: false,

    // ---- info about the current page of results, filled in from the server after every fetch ----
    meta: {
        from: {{ $complaints->firstItem() ?? 0 }},
        to: {{ $complaints->lastItem() ?? 0 }},
        total: {{ $complaints->total() }},
        lastPage: {{ $complaints->lastPage() }},
    },

    // ---- human-readable labels, built once from the PHP enums/collections ----
    statusLabels: @js(collect(ComplaintStatus::cases())->mapWithKeys(fn($s) => [$s->value => $s->label()])),
    categoryLabels: @js($categories->pluck('name', 'id')),
    priorityLabels: @js(collect(ComplaintPriority::cases())->mapWithKeys(fn($p) => [$p->value => $p->label()])),
    sortLabels: {
        '-created_at': 'Newest',
        'created_at': 'Oldest',
        '-updated_at': 'Date Modified'
    },

    // ---- computed helpers ----
    get canGoPrev() { return this.page > 1; },
    get canGoNext() { return this.page < this.meta.lastPage; },

    closeDropdowns() {
        this.statusOpen = false;
        this.categoryOpen = false;
        this.priorityOpen = false;
        this.sortOpen = false;
    },

    // any time a FILTER changes we jump back to page 1 -
    // otherwise you could land on 'page 3' of a filtered list that only has 1 page.
    filterChanged() {
        this.page = 1;
        this.fetchResults();
    },

    changePerPage() {
        this.page = 1;
        this.fetchResults();
    },

    prevPage() {
        if (!this.canGoPrev) return;
        this.page--;
        this.fetchResults();
    },

    nextPage() {
        if (!this.canGoNext) return;
        this.page++;
        this.fetchResults();
    },

    fetchResults() {
        this.loading = true;

        const params = new URLSearchParams();

        if (this.search) {
            params.set('filter[search]', this.search);
        }

        if (this.status) {
            params.set('filter[status]', this.status);
        }

        if (this.category) {
            params.set('filter[complaint_category_id]', this.category);
        }

        if (this.priority) {
            params.set('filter[priority]', this.priority);
        }

        if (this.sort) {
            params.set('sort', this.sort);
        }

        params.set('per_page', this.perPage);
        params.set('page', this.page);

        fetch('{{ route('admin.complaints') }}?' + params.toString(), {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Failed to fetch complaints.');
                }

                return res.json();
            })
            .then(data => {
                document.getElementById('complaints-table').innerHTML = data.html;

                this.meta.from = data.meta.from;
                this.meta.to = data.meta.to;
                this.meta.total = data.meta.total;
                this.meta.lastPage = data.meta.last_page;

                history.pushState({}, '', '?' + params.toString());
            })
            .catch(error => {
                console.error(error);
            })
            .finally(() => {
                this.loading = false;
            });
    }
}" class="flex flex-col h-[calc(100vh-6rem)]"> {{-- CHANGE #1: turned this into a flex column with a real, fixed viewport height --}}

    <div class="flex items-end justify-between gap-2 pb-3 flex-wrap shrink-0"> {{-- CHANGE #2: added shrink-0 --}}
<div class="flex items-end gap-2 flex-wrap">

    {{-- Search --}}
    <div>
        <label class="flex items-center gap-2 bg-base-200 rounded-sm px-3 py-1.5 w-56">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4 opacity-60"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">
                <circle cx="11" cy="11" r="7" />
                <path stroke-linecap="round" d="m21 21-4.3-4.3" />
            </svg>

            <input
                type="text"
                x-model="search"
                x-on:input.debounce.400ms="filterChanged()"
                autocomplete="off"
                class="bg-transparent border-none outline-none text-xs w-full placeholder:text-base-content/50"
                placeholder="Search">
        </label>
    </div>


    {{-- Status --}}
    <div class="relative">
        <button
            type="button"
            @click="statusOpen = !statusOpen; categoryOpen = false; priorityOpen = false; sortOpen = false"
            :class="status !== ''
                ? 'bg-primary/20 text-primary'
                : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
            class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors">

            <span>
                Status:
            </span>

            <span x-text="status !== '' ? statusLabels[status] : 'All'"></span>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-3 h-3 opacity-60"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m6 9 6 6 6-6" />
            </svg>
        </button>

        <div
            x-show="statusOpen"
            x-cloak
            @click.outside="statusOpen = false"
            x-transition
            class="absolute left-0 top-full mt-2 w-44 bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-30">

            <button
                type="button"
                @click="status = ''; statusOpen = false; filterChanged()"
                :class="status === '' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                class="w-full text-left px-3 py-1.5 text-xs">
                All
            </button>

            @foreach (ComplaintStatus::cases() as $s)
                <button
                    type="button"
                    @click="status = '{{ $s->value }}'; statusOpen = false; filterChanged()"
                    :class="status === '{{ $s->value }}' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                    class="w-full text-left px-3 py-1.5 text-xs">
                    {{ $s->label() }}
                </button>
            @endforeach
        </div>
    </div>


    {{-- Category --}}
    <div class="relative">
        <button
            type="button"
            @click="categoryOpen = !categoryOpen; statusOpen = false; priorityOpen = false; sortOpen = false"
            :class="category !== ''
                ? 'bg-primary/20 text-primary'
                : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
            class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors">

            <span>
                Category:
            </span>

            <span x-text="category !== '' ? categoryLabels[category] : 'All'"></span>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-3 h-3 opacity-60"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m6 9 6 6 6-6" />
            </svg>
        </button>

        <div
            x-show="categoryOpen"
            x-cloak
            @click.outside="categoryOpen = false"
            x-transition
            class="absolute left-0 top-full mt-2 w-52 max-h-64 overflow-y-auto bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-30">

            <button
                type="button"
                @click="category = ''; categoryOpen = false; filterChanged()"
                :class="category === '' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                class="w-full text-left px-3 py-1.5 text-xs">
                All
            </button>

            @foreach ($categories as $categoryOption)
                <button
                    type="button"
                    @click="category = '{{ $categoryOption->id }}'; categoryOpen = false; filterChanged()"
                    :class="category === '{{ $categoryOption->id }}' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                    class="w-full text-left px-3 py-1.5 text-xs">
                    {{ $categoryOption->name }}
                </button>
            @endforeach
        </div>
    </div>


    {{-- Priority --}}
    <div class="relative">
        <button
            type="button"
            @click="priorityOpen = !priorityOpen; statusOpen = false; categoryOpen = false; sortOpen = false"
            :class="priority !== ''
                ? 'bg-primary/20 text-primary'
                : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
            class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors">

            <span>
                Priority:
            </span>

            <span x-text="priority !== '' ? priorityLabels[priority] : 'All'"></span>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-3 h-3 opacity-60"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m6 9 6 6 6-6" />
            </svg>
        </button>

        <div
            x-show="priorityOpen"
            x-cloak
            @click.outside="priorityOpen = false"
            x-transition
            class="absolute left-0 top-full mt-2 w-44 bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-30">

            <button
                type="button"
                @click="priority = ''; priorityOpen = false; filterChanged()"
                :class="priority === '' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                class="w-full text-left px-3 py-1.5 text-xs">
                All
            </button>

            @foreach (ComplaintPriority::cases() as $p)
                <button
                    type="button"
                    @click="priority = '{{ $p->value }}'; priorityOpen = false; filterChanged()"
                    :class="priority === '{{ $p->value }}' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                    class="w-full text-left px-3 py-1.5 text-xs">
                    {{ $p->label() }}
                </button>
            @endforeach
        </div>
    </div>


    {{-- Sort --}}
    <div class="relative">
        <button
            type="button"
            @click="sortOpen = !sortOpen; statusOpen = false; categoryOpen = false; priorityOpen = false"
            class="flex items-center gap-1.5 bg-base-200 rounded-full px-3 py-1.5 text-xs font-medium hover:bg-base-300 transition-colors">

            <span>
                Sort:
            </span>

            <span x-text="sortLabels[sort]"></span>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-3 h-3 opacity-60"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m6 9 6 6 6-6" />
            </svg>
        </button>

        <div
            x-show="sortOpen"
            x-cloak
            @click.outside="sortOpen = false"
            x-transition
            class="absolute left-0 top-full mt-2 w-40 bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-30">

            <template
                x-for="[value, label] in Object.entries(sortLabels)"
                :key="value">

                <button
                    type="button"
                    @click="sort = value; sortOpen = false; filterChanged()"
                    :class="sort === value ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                    class="w-full text-left px-3 py-1.5 text-xs"
                    x-text="label">
                </button>

            </template>
        </div>
    </div>

</div>


{{-- View Archived --}}

    <a href="{{ route('admin.complaints.archived') }}"
    class="btn btn-sm btn-outline shrink-0">

    <svg xmlns="http://www.w3.org/2000/svg"
        class="w-3.5 h-3.5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2">
        <rect x="3" y="4" width="18" height="4" rx="1" />
        <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8" />
        <path stroke-linecap="round" d="M10 12h4" />
    </svg>

    View Archived
</a>
</div>



    {{-- Table (this is the ONLY part that scrolls) --}}
    <div class="relative flex-1 min-h-0"> {{-- CHANGE #3: was "relative" only, now flex-1 min-h-0 --}}

        <div x-show="loading" x-transition.opacity
            class="absolute inset-0 bg-base-100/60 flex items-center justify-center z-20 rounded-md">
            <span class="loading loading-spinner loading-sm"></span>
        </div>

        <div class="h-full overflow-y-auto rounded-md scrollbar-thin border border-base-300"> {{-- was max-h-[600px], now h-full --}}
            <div id="complaints-table">
                @include('admin.complaints._table')
            </div>
        </div>

    </div>


    {{-- Pagination bar --}}
    <div class="flex items-center justify-between mt-2 shrink-0"> {{-- CHANGE #4: added shrink-0 --}}

        <span class="text-[11px] text-base-content/60">
            <template x-if="meta.total > 0">
                <span>
                    <span x-text="meta.from"></span>-<span x-text="meta.to"></span> of <span
                        x-text="meta.total"></span>
                </span>
            </template>
            <template x-if="meta.total === 0">
                <span>0 results</span>
            </template>
        </span>

        <div class="flex items-center gap-1.5">

            <div class="relative">
                <button type="button" @click="perPageOpen = !perPageOpen"
                    class="flex items-center gap-1 bg-base-200 rounded-full px-2.5 py-1 text-[11px] hover:bg-base-300">
                    <span x-text="perPage + ' / page'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-60" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <div x-show="perPageOpen" x-cloak @click.outside="perPageOpen = false" x-transition
                    class="absolute bottom-full right-0 mb-2 w-20 bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-30">

                    <template x-for="opt in [10, 25, 50]" :key="opt">
                        <button type="button" @click="perPage = opt; perPageOpen = false; changePerPage()"
                            :class="perPage === opt ? 'bg-base-200 font-medium' : 'hover:bg-base-200'" class="w-full text-left px-3 py-1 text-[11px]" x-text="opt"></button>
                    </template>

                </div>
            </div>

            <button type="button" @click="prevPage()" :disabled="!canGoPrev" :class="!canGoPrev ? 'opacity-30 cursor-not-allowed' : 'hover:bg-base-300'"
                class="w-6 h-6 flex items-center justify-center rounded-full bg-base-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                </svg>
            </button>

            <button type="button" @click="nextPage()" :disabled="!canGoNext" :class="!canGoNext ? 'opacity-30 cursor-not-allowed' : 'hover:bg-base-300'"
                class="w-6 h-6 flex items-center justify-center rounded-full bg-base-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                </svg>
            </button>

        </div>
    </div>

</div>
@endsection
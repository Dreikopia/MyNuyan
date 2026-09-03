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


    <div x-data="{
        // ---- filter value ----
        search: @js(request('filter.search', '')),
    
        // ---- pagination values (same pattern as complaints) ----
        perPage: @js((int) request('per_page', 10)),
        page: @js((int) request('page', 1)),
    
        perPageOpen: false,
        loading: false,
    
        // ---- info about the current page, filled in from the server after every fetch ----
        meta: {
            from: {{ $categories->firstItem() ?? 0 }},
            to: {{ $categories->lastItem() ?? 0 }},
            total: {{ $categories->total() }},
            lastPage: {{ $categories->lastPage() }},
        },
    
        get canGoPrev() { return this.page > 1; },
        get canGoNext() { return this.page < this.meta.lastPage; },
    
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
    
            params.set('per_page', this.perPage);
            params.set('page', this.page);
    
            fetch('{{ route('admin.categories') }}?' + params.toString(), {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Failed to fetch categories.');
                    }
    
                    return res.json();
                })
                .then(data => {
                    document.getElementById('complaint-categories-table').innerHTML = data.html;
    
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
    }" class="flex flex-col h-[calc(100vh-6rem)]">

        <div class="flex items-end justify-between gap-2 pb-3 flex-wrap shrink-0">

            {{-- Search --}}
            <div>
                <label class="flex items-center gap-2 bg-base-200 rounded-sm px-3 py-1.5 w-56">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-60" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path stroke-linecap="round" d="m21 21-4.3-4.3" />
                    </svg>

                    <input type="text" x-model="search" x-on:input.debounce.400ms="filterChanged()"
                        x-on:keydown.enter.prevent="filterChanged()" autocomplete="off"
                        class="bg-transparent border-none outline-none text-xs w-full placeholder:text-base-content/50"
                        placeholder="Search categories">
                </label>
            </div>

            {{-- View Archived --}}
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

        {{-- Table (this is the ONLY part that scrolls) --}}
        <div class="relative flex-1 min-h-0">

            <div x-show="loading" x-transition.opacity
                class="absolute inset-0 bg-base-100/60 flex items-center justify-center z-20 rounded-md">
                <span class="loading loading-spinner loading-sm"></span>
            </div>

            <div class="h-full overflow-y-auto rounded-md scrollbar-thin border border-base-300">
                <div id="complaint-categories-table">
                    @include('admin.complaints.categories._table')
                </div>
            </div>

        </div>

        {{-- Pagination bar --}}
        <div class="flex items-center justify-between mt-2 shrink-0">

            <span class="text-[11px] text-base-content/60">
                <template x-if="meta.total > 0">
                    <span>
                        <span x-text="meta.from"></span>-<span x-text="meta.to"></span> of
                        <span x-text="meta.total"></span>
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

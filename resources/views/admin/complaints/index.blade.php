@extends('layouts.admin')

@php
    use App\Enums\ComplaintStatus;
    use App\Enums\ComplaintPriority;
@endphp

@section('content')
    <x-admin.header title="Complaints">

        <x-button class="btn bg-primary/50 rounded-t-xl rounded-bl-xl rounded-br-none" href="{{ route('admin.categories') }}">
            Manage Categories
        </x-button>

        <x-icons.notifications />

    </x-admin.header>


    <div x-data="{
        search: @js(request('filter.search', '')),
        status: @js(request('filter.status', '')),
        category: @js(request('filter.complaint_category_id', '')),
        priority: @js(request('filter.priority', '')),
        sort: @js(request('sort', '-created_at')),
    
        filterOpen: false,
        sortOpen: false,
        loading: false,
    
        categoryLabels: @js($categories->pluck('name', 'id')),
    
        priorityLabels: @js(collect(ComplaintPriority::cases())->mapWithKeys(fn($p) => [$p->value => $p->label()])),
    
        sortLabels: {
            '-created_at': 'Newest',
            'created_at': 'Oldest',
            '-updated_at': 'Date Modified'
        },
    
        get hasActiveFilters() {
            return this.category !== '' || this.priority !== '';
        },
    
        clearCategory() {
            this.category = '';
            this.fetchResults();
        },
    
        clearPriority() {
            this.priority = '';
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
                params.set(
                    'filter[complaint_category_id]',
                    this.category
                );
            }
    
            if (this.priority) {
                params.set('filter[priority]', this.priority);
            }
    
            if (this.sort) {
                params.set('sort', this.sort);
            }
    
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
                    document.getElementById('complaints-pagination').innerHTML = data.pagination;
    
                    history.pushState({},
                        '',
                        '?' + params.toString()
                    );
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }" x-init="document.addEventListener('click', (e) => {
        const link = e.target.closest('#complaints-pagination a');
    
        if (!link) {
            return;
        }
    
        e.preventDefault();
    
        const url = new URL(link.href);
    
        loading = true;
    
        fetch(url, {
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
                document.getElementById('complaints-pagination').innerHTML = data.pagination;
    
                history.pushState({}, '', url);
            })
            .catch(error => {
                console.error(error);
            })
            .finally(() => {
                loading = false;
            });
    });">

        {{-- Row 1: Search, Filter, Sort + Active Filter Pills --}}
        <div class="flex items-center gap-2 py-4 flex-wrap">

            {{-- Search --}}
            <label class="flex items-center gap-2 bg-base-200 rounded-sm px-3 py-1.5 w-70">
                <x-icons.search />
                <input type="text" x-model="search" x-on:input.debounce.400ms="fetchResults()" autocomplete="off"
                    class="bg-transparent border-none outline-none text-xs w-full placeholder:text-base-content/50"
                    placeholder="Search">
            </label>

            {{-- Sort --}}
            <div class="relative">
                <button type="button" @click="sortOpen = !sortOpen; filterOpen = false"
                    class="flex items-center gap-1.5 bg-base-200 rounded-full pl-3 pr-2 py-1.5 text-xs font-medium hover:bg-base-300 transition-colors">
                    <x-icons.sort />
                    <span>
                        Sort:
                        <span x-text="sortLabels[sort]"></span>
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-60" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                    </svg>

                </button>


                {{-- Sort Popover --}}
                <div x-show="sortOpen" x-cloak @click.outside="sortOpen = false" x-transition
                    class="absolute left-0 top-full mt-2 w-40 bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-30">

                    <template x-for="[value, label] in Object.entries(sortLabels)" :key="value">

                        <button type="button" @click="sort = value; sortOpen = false; fetchResults()" :class="sort === value ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                            class="w-full text-left px-3 py-1.5 text-xs" x-text="label"></button>

                    </template>

                </div>

            </div>

            {{-- Filter --}}
            <div class="relative">
                <button type="button" @click="filterOpen = !filterOpen; sortOpen = false"
                    class="flex items-center gap-1.5 bg-base-200 rounded-full pl-3 pr-2 py-1.5 text-xs font-medium hover:bg-base-300 transition-colors">
                    <x-icons.filter />
                    Filter
                    <span x-show="hasActiveFilters" x-cloak
                        class="bg-base-content text-base-100 rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-semibold"
                        x-text="(category !== '' ? 1 : 0) + (priority !== '' ? 1 : 0)"></span>
                </button>


                {{-- Filter Popover --}}
                <div x-show="filterOpen" x-cloak @click.outside="filterOpen = false" x-transition
                    class="absolute left-0 top-full mt-2 w-56 bg-base-100 border border-base-300 rounded-box shadow-lg p-3 z-30 space-y-3">

                    {{-- Category --}}
                    <div>

                        <label class="text-[11px] font-medium text-base-content/60 uppercase">
                            Category
                        </label>

                        <select x-model="category" @change="fetchResults()"
                            class="select select-bordered select-sm w-full mt-1">
                            <option value="">
                                All categories
                            </option>

                            @foreach ($categories as $categoryOption)
                                <option value="{{ $categoryOption->id }}">
                                    {{ $categoryOption->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- Priority --}}
                    <div>
                        <label class="text-[11px] font-medium text-base-content/60 uppercase">
                            Priority
                        </label>
                        <select x-model="priority" @change="fetchResults()"
                            class="select select-bordered select-sm w-full mt-1">
                            <option value="">
                                All priorities
                            </option>
                            @foreach (ComplaintPriority::cases() as $p)
                                <option value="{{ $p->value }}">
                                    {{ $p->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Active Category Pill --}}
            <template x-if="category !== ''">

                <span
                    class="inline-flex items-center gap-1 pl-2.5 pr-1.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium">

                    Category:

                    <span x-text="categoryLabels[category]"></span>

                    <button type="button" @click="clearCategory()" class="hover:bg-primary/20 rounded-full p-0.5">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12" />
                        </svg>

                    </button>

                </span>

            </template>


            {{-- Active Priority Pill --}}
            <template x-if="priority !== ''">

                <span
                    class="inline-flex items-center gap-1 pl-2.5 pr-1.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium">

                    Priority:

                    <span x-text="priorityLabels[priority]"></span>

                    <button type="button" @click="clearPriority()" class="hover:bg-primary/20 rounded-full p-0.5">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12" />
                        </svg>

                    </button>

                </span>

            </template>

        </div>


        {{-- Row 2: Status pills + View Archived, inline together --}}
        <div class="flex items-center justify-between gap-2 pb-3 flex-wrap">
            <div class="flex items-center gap-1.5 flex-wrap">
                <button type="button" @click="status = ''; fetchResults()" :class="status === '' ? 'bg-base-content text-base-100' : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
                    class="px-3 py-1 rounded-full text-xs font-medium transition-colors">
                    All <span class="opacity-70">{{ $statusCounts->get('all', 0) }}</span>
                </button>

                @foreach (ComplaintStatus::cases() as $s)
                    <button type="button" @click="status = '{{ $s->value }}'; fetchResults()" :class="status === '{{ $s->value }}' ? 'bg-base-content text-base-100' : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
                        class="px-3 py-1 rounded-full text-xs font-medium transition-colors">
                        {{ $s->label() }} <span class="opacity-70">{{ $statusCounts->get($s->value, 0) }}</span>
                    </button>
                @endforeach
            </div>

            <a href="{{ route('admin.complaints.archived') }}" class="btn btn-sm btn-outline shrink-0">
                <x-icons.archive class="w-3.5 h-3.5" />
                View Archived
            </a>
        </div>


        {{-- <div class="flex justify-end mb-2">
            <div id="complaints-pagination">
                {{ $complaints->links('vendor.pagination.compact') }}
            </div>
        </div> --}}


        {{-- Table --}}
        <div class="relative">

            {{-- Loading Overlay --}}
            <div x-show="loading" x-transition.opacity
                class="absolute inset-0 bg-base-100/60 flex items-center justify-center z-20 rounded-md">
                <span class="loading loading-spinner loading-sm"></span>
            </div>


            {{-- Complaints Table --}}
            <div class="max-h-[600px] overflow-y-auto rounded-md scrollbar-thin border border-base-300">

                <div id="complaints-table">
                    @include('admin.complaints._table')
                </div>

            </div>

        </div>

    </div>
@endsection

@extends('layouts.admin')

@php
    use App\Enums\ComplaintPriority;
@endphp

@section('content')
    <x-admin.header title="Complaints" :title-url="route('admin.complaints')" :breadcrumbs="[['label' => 'Archived Complaints']]">
    </x-admin.header>

    <div x-data="{
        search: @js(request('filter.search', '')),
        category: @js(request('filter.complaint_category_id', '')),
        priority: @js(request('filter.priority', '')),

        categoryOpen: false,
        priorityOpen: false,

        loading: false,

        categoryLabels: @js($categories->pluck('name', 'id')),
        priorityLabels: @js(collect(ComplaintPriority::cases())->mapWithKeys(fn($p) => [$p->value => $p->label()])),

        closeDropdowns() {
            this.categoryOpen = false;
            this.priorityOpen = false;
        },

        fetchResults() {
            this.loading = true;

            const params = new URLSearchParams();

            if (this.search) params.set('filter[search]', this.search);
            if (this.category) params.set('filter[complaint_category_id]', this.category);
            if (this.priority) params.set('filter[priority]', this.priority);

            fetch('{{ route('admin.complaints.archived') }}?' + params.toString(), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => {
                    return res.json();
                })
                .then(data => {
                    document.getElementById('complaints-table').innerHTML = data.html;

                    history.pushState({}, '', '?' + params.toString());
                })
                .catch(error => console.error(error))
                .finally(() => {
                    this.loading = false;
                });
        }
    }" class="flex flex-col h-[calc(100vh-6rem)]">

        <div class="flex items-end justify-between gap-2 pb-3 flex-wrap shrink-0">
            <div class="flex items-end gap-2 flex-wrap">

                {{-- Search --}}
                <div>
                    <label class="flex items-center gap-2 bg-base-200 rounded-sm px-3 py-1.5 w-56">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-60" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path stroke-linecap="round" d="m21 21-4.3-4.3" />
                        </svg>

                        <input type="text" x-model="search" x-on:input.debounce.400ms="fetchResults()"
                            autocomplete="off"
                            class="bg-transparent border-none outline-none text-xs w-full placeholder:text-base-content/50"
                            placeholder="Search">
                    </label>
                </div>

                {{-- Category --}}
                <div class="relative">
                    <button type="button" @click="categoryOpen = !categoryOpen; priorityOpen = false"
                        :class="category !== ''
                            ? 'bg-primary/20 text-primary'
                            : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
                        class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors">

                        <span>Category:</span>
                        <span x-text="category !== '' ? categoryLabels[category] : 'All'"></span>

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-60" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div x-show="categoryOpen" x-cloak @click.outside="categoryOpen = false" x-transition
                        class="absolute left-0 top-full mt-2 w-52 max-h-64 overflow-y-auto bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-30">

                        <button type="button" @click="category = ''; categoryOpen = false; fetchResults()"
                            :class="category === '' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                            class="w-full text-left px-3 py-1.5 text-xs">
                            All
                        </button>

                        @foreach ($categories as $categoryOption)
                            <button type="button"
                                @click="category = '{{ $categoryOption->id }}'; categoryOpen = false; fetchResults()"
                                :class="category === '{{ $categoryOption->id }}' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                                class="w-full text-left px-3 py-1.5 text-xs">
                                {{ $categoryOption->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Priority --}}
                <div class="relative">
                    <button type="button" @click="priorityOpen = !priorityOpen; categoryOpen = false"
                        :class="priority !== ''
                            ? 'bg-primary/20 text-primary'
                            : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
                        class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors">

                        <span>Priority:</span>
                        <span x-text="priority !== '' ? priorityLabels[priority] : 'All'"></span>

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-60" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div x-show="priorityOpen" x-cloak @click.outside="priorityOpen = false" x-transition
                        class="absolute left-0 top-full mt-2 w-44 bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-30">

                        <button type="button" @click="priority = ''; priorityOpen = false; fetchResults()"
                            :class="priority === '' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                            class="w-full text-left px-3 py-1.5 text-xs">
                            All
                        </button>

                        @foreach (ComplaintPriority::cases() as $p)
                            <button type="button"
                                @click="priority = '{{ $p->value }}'; priorityOpen = false; fetchResults()"
                                :class="priority === '{{ $p->value }}' ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                                class="w-full text-left px-3 py-1.5 text-xs">
                                {{ $p->label() }}
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Back to active complaints --}}
            <a href="{{ route('admin.complaints') }}" class="btn btn-sm btn-outline shrink-0">
                <x-icons.back />
                Active Complaints
            </a>

        </div>

        {{-- Table (this is the ONLY part that scrolls) --}}
        <div class="relative flex-1 min-h-0">

            {{-- Loading Overlay --}}
            <div x-show="loading" x-transition.opacity
                class="absolute inset-0 bg-base-100/60 flex items-center justify-center z-20 rounded-md">
                <span class="loading loading-spinner loading-sm"></span>
            </div>

            <div class="h-full overflow-y-auto rounded-md scrollbar-thin border border-base-300">
                <div id="complaints-table">
                    @include('admin.complaints._table', [
                        'complaints' => $complaints,
                        'archivedView' => true,
                    ])
                </div>
            </div>

        </div>

    </div>
@endsection
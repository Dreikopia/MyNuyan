@extends('layouts.admin')

@section('content')
    <x-admin.header title="News and Announcements" description="Manage News and announcements">
        <x-admin.news-form :categories="$categories" />
    </x-admin.header>
    <div x-data="{
        search: @js(request('search', '')),
        status: @js(request('status', '')),
        category: @js(request('category', '')),
        sort: @js(request('sort', '-created_at', '-updated_at')),
    
        sortOpen: false,
        sortLabels: {
            '-created_at': 'Newest',
            'created_at': 'Oldest',
            '-updated_at': 'Recently Modified'
        },
    
        open: false,
        loading: false,
        content: '',
    
        fetchResults() {
            const params = new URLSearchParams();
    
            // Spatie reads filters from filter[key]=value, not key=value.
            if (this.search) params.set('filter[search]', this.search);
            if (this.status) params.set('filter[status]', this.status);
            if (this.category) params.set('filter[category]', this.category);
            if (this.sort) params.set('sort', this.sort);
    
            fetch('{{ route('admin.news') }}?' + params.toString(), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch news.');
                    return res.json();
                })
                .then(data => {
                    const grid = document.getElementById('news-grid');
                    grid.innerHTML = data.html;
                    this.$nextTick(() => Alpine.initTree(grid));
                    history.pushState({}, '', '?' + params.toString());
                })
                .catch(error => console.error(error));
        },
    
        async openDrawer(url) {
            this.open = true;
            this.loading = true;
    
            try {
                const res = await fetch(url);
                this.content = await res.text();
                this.$nextTick(() => Alpine.initTree(this.$refs.drawerPanel));
            } catch (error) {
                console.error(error);
                this.content = '<p class=\'p-6 text-error\'>Failed to load content.</p>';
            } finally {
                this.loading = false;
            }
        }
    }">

        {{-- Single inlined toolbar: search + category + status + sort, all one row --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">


            <label class="flex items-center gap-2 bg-base-200 rounded-sm px-3 py-1.5 w-70">
                <x-icons.search />
                <input type="text" x-model="search" x-on:input.debounce.400ms="fetchResults()" autocomplete="off"
                    class="bg-transparent border-none outline-none text-xs w-full placeholder:text-base-content/50"
                    placeholder="Search">
            </label>

            {{-- Everything else sits inline, right-aligned on desktop --}}
            <div class="flex items-center gap-2 sm">
                <select x-model="category" x-on:change="fetchResults()"
                    class="select select-bordered select-sm w-auto min-w-36 whitespace-nowrap">
                    <option value="">All categories</option>

                    @foreach ($categories as $categoryOption)
                        <option value="{{ $categoryOption->id }}">
                            {{ $categoryOption->name }}
                        </option>
                    @endforeach
                </select>

                <select x-model="status" x-on:change="fetchResults()"
                    class="select select-bordered select-sm w-auto min-w-32 whitespace-nowrap">
                    <option value="">All statuses</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>

                {{-- Sort pill+popover — the one sort control, styled to match --}}
                <div class="relative">
                    <button type="button" @click="sortOpen = !sortOpen"
                        class="flex items-center gap-1.5 h-8 bg-base-200 rounded-full pl-3 pr-2 text-xs font-medium hover:bg-base-300 transition-colors whitespace-nowrap">
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap">
                            <x-icons.sort />
                            <span x-text="sortLabels[sort]"></span>
                        </span>
                    </button>

                    <div x-show="sortOpen" x-cloak @click.outside="sortOpen = false" x-transition
                        class="absolute right-0 top-full mt-2 w-40 bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-30">
                        <template x-for="[value, label] in Object.entries(sortLabels)" :key="value">
                            <button type="button" @click="sort = value; sortOpen = false; fetchResults()" :class="sort === value ? 'bg-base-200 font-medium' : 'hover:bg-base-200'"
                                class="w-full text-left px-3 py-1.5 text-xs" x-text="label"></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cards grid --}}
        <div id="news-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @include('admin.news.partials._grid', ['news' => $news])
        </div>

        {{-- Drawer --}}
        <div class="drawer drawer-end">
            <input type="checkbox" class="drawer-toggle" x-model="open" />
            <div class="drawer-side z-50">
                <label @click="open = false" class="drawer-overlay"></label>
                <div x-ref="drawerPanel" class="bg-base-100 min-h-full w-full max-w-xl lg:max-w-2xl flex flex-col">
                    <template x-if="loading">
                        <div class="flex flex-1 justify-center items-center">
                            <span class="loading loading-spinner text-primary">Loading</span>
                        </div>
                    </template>
                    <div x-show="!loading" x-html="content" class="flex-1"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

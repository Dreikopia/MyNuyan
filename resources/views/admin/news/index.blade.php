@extends('layouts.admin')

@section('content')

    <x-admin.header title="News and Announcements">

        <x-admin.news-form :categories="$categories" />

    </x-admin.header>

    <div
        x-data="{
            search: @js(request('filter.search', '')),
            status: @js(request('filter.status', '')),
            category: @js(request('filter.news_category_id', '')),
            sort: @js(request('sort', '-created_at')),
            statusOpen: false,
            categoryOpen: false,
            sortOpen: false,
            open: false,
            loading: false,
            drawerLoading: false,
            content: '',
            categoryLabels: @js($categories->pluck('name', 'id')),
            statusCounts: @js($statusCounts),
            sortLabels: {
                '-created_at': 'Newest',
                'created_at': 'Oldest',
                '-updated_at': 'Recently Modified'
            },

            filterChanged() {
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
                    params.set('filter[news_category_id]', this.category);
                }

                if (this.sort) {
                    params.set('sort', this.sort);
                }

                fetch('{{ route('admin.news') }}?' + params.toString(), {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Failed to fetch news.');
                        }

                        return res.json();
                    })
                    .then(data => {
                        document.getElementById('news-grid').innerHTML = data.html;

                        if (data.statusCounts) {
                            this.statusCounts = data.statusCounts;
                        }

                        history.pushState(
                            {},
                            '',
                            params.toString()
                                ? '?' + params.toString()
                                : window.location.pathname
                        );

                        this.$nextTick(() => {
                            Alpine.initTree(
                                document.getElementById('news-grid')
                            );
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            async openDrawer(url) {
                this.open = true;
                this.drawerLoading = true;

                try {
                    const res = await fetch(url);

                    if (!res.ok) {
                        throw new Error('Failed to load news.');
                    }

                    this.content = await res.text();

                    this.$nextTick(() => {
                        Alpine.initTree(this.$refs.drawerPanel);
                    });
                } catch (error) {
                    console.error(error);
                    this.content = '<p class=&quot;p-6 text-error&quot;>Failed to load content.</p>';
                } finally {
                    this.drawerLoading = false;
                }
            }
        }"
        class="flex flex-col h-[calc(100vh-6rem)]"
    >

        <div class="flex items-end gap-2 pb-3 shrink-0 min-w-0">

            <div class="flex items-center gap-1.5 shrink-0">

                <button
                    type="button"
                    @click="status = ''; filterChanged()"
                    :class="status === ''
                        ? 'bg-primary/20 text-primary'
                        : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
                    class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors"
                >
                    <span>All</span>

                    <span
                        class="text-[10px] opacity-60"
                        x-text="statusCounts.all"
                    ></span>
                </button>

                <button
                    type="button"
                    @click="status = 'published'; filterChanged()"
                    :class="status === 'published'
                        ? 'bg-success/20 text-success'
                        : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
                    class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors"
                >
                    <span>Published</span>

                    <span
                        class="text-[10px] opacity-60"
                        x-text="statusCounts.published"
                    ></span>
                </button>

                <button
                    type="button"
                    @click="status = 'draft'; filterChanged()"
                    :class="status === 'draft'
                        ? 'bg-warning/20 text-warning'
                        : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
                    class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors"
                >
                    <span>Draft</span>

                    <span
                        class="text-[10px] opacity-60"
                        x-text="statusCounts.draft"
                    ></span>
                </button>

            </div>

            <div class="shrink-0 ml-50">

                <label class="flex items-center gap-2 bg-base-200 rounded-sm px-3 py-1.5 w-56">

                    <x-icons.search />

                    <input
                        type="text"
                        x-model="search"
                        x-on:input.debounce.400ms="filterChanged()"
                        autocomplete="off"
                        class="bg-transparent border-none outline-none text-xs w-full placeholder:text-base-content/50"
                        placeholder="Search"
                    >

                </label>

            </div>

            <div class="relative shrink-0">

                <button
                    type="button"
                    @click="
                        categoryOpen = !categoryOpen;
                        statusOpen = false;
                        sortOpen = false;
                    "
                    :class="category !== ''
                        ? 'bg-primary/20 text-primary'
                        : 'bg-base-200 text-base-content/70 hover:bg-base-300'"
                    class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors whitespace-nowrap"
                >
                    <span>Category:</span>

                    <span
                        x-text="category !== '' ? categoryLabels[category] : 'All'"
                    ></span>

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-3 h-3 opacity-60"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m6 9 6 6 6-6"
                        />
                    </svg>
                </button>

                <div
                    x-show="categoryOpen"
                    x-cloak
                    @click.outside="categoryOpen = false"
                    x-transition
                    class="absolute left-0 top-full mt-2 w-52 max-h-64 overflow-y-auto bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-50"
                >

                    <button
                        type="button"
                        @click="
                            category = '';
                            categoryOpen = false;
                            filterChanged();
                        "
                        :class="category === ''
                            ? 'bg-base-200 font-medium'
                            : 'hover:bg-base-200'"
                        class="w-full text-left px-3 py-1.5 text-xs"
                    >
                        All
                    </button>

                    @foreach ($categories as $categoryOption)

                        <button
                            type="button"
                            @click="
                                category = @js((string) $categoryOption->id);
                                categoryOpen = false;
                                filterChanged();
                            "
                            :class="category === @js((string) $categoryOption->id)
                                ? 'bg-base-200 font-medium'
                                : 'hover:bg-base-200'"
                            class="w-full text-left px-3 py-1.5 text-xs"
                        >
                            {{ $categoryOption->name }}
                        </button>

                    @endforeach

                </div>

            </div>

            <div class="relative shrink-0">

                <button
                    type="button"
                    @click="
                        sortOpen = !sortOpen;
                        statusOpen = false;
                        categoryOpen = false;
                    "
                    class="flex items-center gap-1.5 bg-base-200 rounded-full px-3 py-1.5 text-xs font-medium hover:bg-base-300 transition-colors whitespace-nowrap"
                >
                    <x-icons.sort />

                    <span>Sort:</span>

                    <span x-text="sortLabels[sort]"></span>

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-3 h-3 opacity-60"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m6 9 6 6 6-6"
                        />
                    </svg>
                </button>

                <div
                    x-show="sortOpen"
                    x-cloak
                    @click.outside="sortOpen = false"
                    x-transition
                    class="absolute left-0 top-full mt-2 w-44 bg-base-100 border border-base-300 rounded-box shadow-lg py-1 z-50"
                >

                    <template
                        x-for="[value, label] in Object.entries(sortLabels)"
                        :key="value"
                    >
                        <button
                            type="button"
                            @click="
                                sort = value;
                                sortOpen = false;
                                filterChanged();
                            "
                            :class="sort === value
                                ? 'bg-base-200 font-medium'
                                : 'hover:bg-base-200'"
                            class="w-full text-left px-3 py-1.5 text-xs"
                            x-text="label"
                        ></button>
                    </template>

                </div>

            </div>

        </div>

        <div class="relative flex-1 min-h-0">

            <div
                x-show="loading"
                x-transition.opacity
                class="absolute inset-0 bg-base-100/60 flex items-center justify-center z-20 rounded-md"
            >
                <span class="loading loading-spinner loading-sm"></span>
            </div>

            <div class="h-full overflow-y-auto rounded-md scrollbar-thin">

                <div
                    id="news-grid"
                    class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3"
                >
                    @include('admin.news.partials._grid', ['news' => $news])
                </div>

            </div>

        </div>

        <div class="drawer drawer-end">

            <input
                type="checkbox"
                class="drawer-toggle"
                x-model="open"
            />

            <div class="drawer-side z-50">

                <label
                    @click="open = false"
                    class="drawer-overlay"
                ></label>

                <div
                    x-ref="drawerPanel"
                    class="bg-base-100 min-h-full w-full max-w-xl lg:max-w-2xl flex flex-col"
                >

                    <template x-if="drawerLoading">

                        <div class="flex flex-1 justify-center items-center">

                            <span class="loading loading-spinner text-primary">
                                Loading
                            </span>

                        </div>

                    </template>

                    <div
                        x-show="!drawerLoading"
                        x-html="content"
                        class="flex-1"
                    ></div>

                </div>

            </div>

        </div>

    </div>

@endsection

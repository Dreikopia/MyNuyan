@extends('layouts.admin')

@section('content')
    <x-admin.header title="News & Announcements">
        <x-admin.news-modal :categories="$categories" />
    </x-admin.header>

    <div class="flex items-center justify-end pb-2" x-data>

        <form method="GET" action="{{ route('admin.news') }}" @input.debounce.300ms="$el.submit()"
            class="flex items-center gap-2">

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.news', request()->except('status')) }}"
                    class="btn btn-xs {{ !request('status') ? 'btn-primary' : 'btn-outline' }}">
                    All
                </a>

                <a href="{{ route('admin.news', array_merge(request()->query(), ['status' => 'published'])) }}"
                    class="btn btn-xs {{ request('status') === 'published' ? 'btn-primary' : 'btn-outline' }}">
                    Published
                </a>

                <a href="{{ route('admin.news', array_merge(request()->query(), ['status' => 'draft'])) }}"
                    class="btn btn-xs {{ request('status') === 'draft' ? 'btn-primary' : 'btn-outline' }}">
                    Draft
                </a>
            </div>
            {{-- Search --}}
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search news and announcements"
                class="input input-sm bg-surface w-120">

            <select name="category" class="select select-sm bg-surface w-auto" @change="$el.form.submit()">
                <option value="" class="text-sm">All</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

        </form>
    </div>


    <div x-data="{
        open: false,
        loading: false,
        content: '',
    
        async openDrawer(url) {
            this.open = true;
            this.loading = true;
    
            try {
                const res = await fetch(url);
                this.content = await res.text();
    
                this.$nextTick(() => {
                    Alpine.initTree(this.$refs.drawerPanel);
                });
            } catch (error) {
                console.error(error);
                this.content = '<p class=\'p-6 text-error\'>Failed to load content.</p>';
            } finally {
                this.loading = false;
            }
        }
    }" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">

        @foreach ($news as $post)
            <div class="card bg-base-100 shadow-sm cursor-pointer hover:shadow-xl transition"
                @click="openDrawer('{{ route('admin.news.drawer', $post) }}')">

                <div class="card-body p-4">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-koho text-muted-foreground">
                                {{ ucfirst($post->status->value) }}
                            </p>
                        </div>
                        <div class="badge badge-sm bg-primary/50">
                            {{ $post->category->name }}
                        </div>
                    </div>


                    <h2 class="card-title text-base">
                        {{ $post->title }}
                    </h2>
                    <p class="text-xs text-base-content/60">
                        {{ \Carbon\Carbon::parse($post->date)->format('F j, Y') }}
                    </p>
                    @if ($post->image_path)
                        <p class="text-xs text-muted-foreground">
                            With image
                        </p>
                    @else
                        <p class="text-xs text-muted-foreground">
                            No image
                        </p>
                    @endif
                </div>
            </div>
        @endforeach


        <div class="drawer drawer-end">

            <input type="checkbox" class="drawer-toggle" x-model="open" />
            <div class="drawer-side z-50">

                <label @click="open = false" class="drawer-overlay"></label>
                <div x-ref="drawerPanel" class="bg-base-100 min-h-full w-full max-w-xl lg:max-w-2xl flex flex-col">
                    <template x-if="loading">
                        <div class="flex flex-1 justify-center items-center">
                            <span class="loading loading-spinner text-primary">
                                Loading
                            </span>
                        </div>
                    </template>

                    <div x-show="!loading" x-html="content" class="flex-1"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <x-admin.header title="News & Announcements">
        <x-admin.news-modal :categories="$categories" />
    </x-admin.header>


    <div class="py-4">
        <div>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search news and announcements"
                class="input input-sm bg-surface w-full" onchange="this.form.submit()">
        </div>
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
    }" class="columns-1 md:columns-2 lg:columns-3 gap-4">




        @foreach ($news as $post)
            <div class="card bg-base-100 w-full mb-3 break-inside-avoid overflow-hidden shadow-sm cursor-pointer hover:shadow-xl transition"
                @click="openDrawer('{{ route('admin.news.drawer', $post) }}')">

                {{-- Image --}}
                @if ($post->image_path)
                    <figure>
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                            class="w-full h-42 object-cover">
                    </figure>
                @endif

                {{-- Content --}}
                <div class="card-body p-4">

                    {{-- Title --}}
                    <h2 class="card-title">
                        {{ $post->title }}
                    </h2>

                    {{-- Category --}}
                    <div class="badge bg-primary/50">
                        {{ $post->category->name }}
                    </div>

                    {{-- Description --}}
                    <p>
                        {{ $post->description }}
                    </p>

                </div>
            </div>
        @endforeach


        {{-- Drawer --}}
        <div class="drawer drawer-end">

            <input type="checkbox" class="drawer-toggle" x-model="open" />

            <div class="drawer-side z-50">

                {{-- Overlay --}}
                <label @click="open = false" class="drawer-overlay"></label>

                {{-- Drawer Panel --}}
                <div x-ref="drawerPanel" class="bg-base-100 min-h-full w-full max-w-xl lg:max-w-2xl flex flex-col">

                    {{-- Loading --}}
                    <template x-if="loading">
                        <div class="flex flex-1 justify-center items-center">
                            <span class="loading loading-spinner text-primary"></span>
                        </div>
                    </template>

                    {{-- Content --}}
                    <div x-show="!loading" x-html="content" class="flex-1"></div>

                </div>
            </div>
        </div>

    </div>
@endsection

@forelse ($news as $post)
    <div class="card bg-surface shadow-sm border border-surface border-t-none cursor-pointer 
                hover:shadow-lg hover:-translate-y-1 transition-all duration-200"
        @click="openDrawer('{{ route('admin.news.drawer', $post) }}')">

        <figure class="relative h-30">
            @if ($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                    class="w-full h-full object-cover rounded-3xl">
            @else
                <div class="w-full h-full flex items-center justify-center text-base-content/30">
                    <span class="text-xs">No image</span>
                </div>
            @endif
        </figure>

        <div class="card-body p-3 gap-2">
            <div class="flex items-center justify-between gap-2">
                <div class="badge badge-sm badge-outline">
                    {{ $post->category->name }}
                </div>
                <p class="text-xs text-base-content/50">
                    {{ \Carbon\Carbon::parse($post->date)->format('M j, Y') }}
                </p>
            </div>

            <h2 class="card-title text-base leading-snug line-clamp-2">
                {{ $post->title }}
            </h2>
            <p class="text-xs text-muted-foreground line-clamp-2">
                {{ $post->description }}
            </p>
        </div>
    </div>
@empty
    <div class="col-span-full flex flex-col items-center justify-center text-center gap-1 py-12">
        <p class="text-base-content/60">No news or announcements found.</p>
        <p class="text-sm text-base-content/40">Try adjusting your search.</p>
    </div>
@endforelse

{{-- drawer-content.blade.php --}}
<div class="flex flex-col flex-1 overflow-hidden" x-data="{ lightboxOpen: false }">

    {{-- Header --}}
    <div class="flex items-center justify-between p-6 pb-3">
        <button type="button" @click="open = false" class="btn btn-ghost btn-square btn-sm">
            <x-icons.back />
        </button>

        <div class="flex-1 px-3">
            <h3 class="text-lg font-bold font-koho">Back</h3>
        </div>


        <button type="button" onclick="document.getElementById('EditNews{{ $post->id }}').showModal()"
            class="btn btn-ghost btn-square btn-sm" aria-label="Edit">
            <x-icons.edit />
        </button>

        <x-modal id="DeleteNews{{ $post->id }}" name="Delete" boxClass="max-w-sm"
            class="btn btn-sm btn-error btn-outline">
            <div class="flex flex-col gap-4">
                <div>
                    <h3 class="font-bold text-lg">Delete News?</h3>
                    <p class="text-sm text-base-content/70 mt-1">
                        Are you sure you want to delete
                        <span class="font-semibold">{{ $post->name }}</span>?
                        This action cannot be undone.
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.news.delete', $post) }}">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-2">
                        <button type="button" class="btn btn-outline"
                            onclick="document.getElementById('DeleteNews{{ $post->id }}').close()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-error">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>

    </div>

    <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
        @if ($post->image_path)
            <button type="button" @click="lightboxOpen = true"
                class="group relative w-full block rounded-lg overflow-hidden cursor-zoom-in">
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                    class="w-full h-full max-h-64 object-cover transition-transform duration-300 group-hover:scale-105">

                <div
                    class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/30 transition-colors">
                    <span
                        class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1.5 text-white text-sm font-medium bg-black/50 px-3 py-1.5 rounded-full">
                        <x-icons.expand class="w-4 h-4" />
                        View full image
                    </span>
                </div>
            </button>
        @endif

        <div class="space-y-3">
            <div>
                <p class="text-xs uppercase text-base-content/50 font-semibold">Title</p>
                <div class="flex items-center space-x-2">
                    <p class=" text-2xl font-bold font-kopub">{{ $post->title }}</p>
                    <div class="badge bg-primary/50">{{ $post->category->name }}</div>
                </div>
            </div>
            <div>
                <p class="text-xs uppercase text-base-content/50 font-semibold">Description</p>
                <p class="text-base whitespace-pre-line">{{ $post->description }}</p>
            </div>
        </div>
    </div>
    <x-admin.news-form :categories="$categories" :post="$post" />

    {{-- Lightbox: teleported to <body> so it can cover the whole screen, --}}
    {{-- not just this drawer (which has its own overflow/stacking context). --}}
    @if ($post->image_path)
        <template x-teleport="body">
            <div x-show="lightboxOpen" x-cloak @keydown.escape.window="lightboxOpen = false" x-transition.opacity
                class="fixed inset-0 z-[999] flex items-center justify-center bg-black/90 p-4 md:p-10"
                @click="lightboxOpen = false">

                <button type="button" @click="lightboxOpen = false"
                    class="btn btn-circle btn-ghost text-white absolute top-4 right-4" aria-label="Close">
                    X
                </button>

                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" @click.stop
                    class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>
        </template>
    @endif

</div>

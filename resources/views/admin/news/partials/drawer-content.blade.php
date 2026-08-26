{{-- drawer-content.blade.php --}}
<div class="flex flex-col flex-1 overflow-hidden">

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
            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                class="w-full h-full max-h-64 object-cover rounded-lg">
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
    <x-admin.news-modal :categories="$categories" :post="$post" />
</div>

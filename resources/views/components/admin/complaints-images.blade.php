@props(['complaint'])

@if ($complaint->images->isNotEmpty())
    <div class="mb-4" x-data="{ open: false, activeImage: null }">
        <p class="text-xs tracking-wide text-base-content/50 mb-2">
            Photos
        </p>

        <div class="flex flex-wrap gap-2">
            @foreach ($complaint->images->take(5) as $image)
                @php
                    $imageUrl = asset('storage/' . $image->image_path);
                @endphp

                <div class="w-16 h-16 overflow-hidden rounded-md border border-base-300 cursor-pointer">
                    <img src="{{ $imageUrl }}" alt="Complaint photo"
                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-125"
                        @click="open = true; activeImage = '{{ $imageUrl }}'">
                </div>
            @endforeach
        </div>

        <!-- Fullscreen lightbox, teleported to <body> so it escapes the drawer's stacking context -->
        <template x-teleport="body">
            <div x-show="open" x-cloak @click="open = false"
                class="fixed inset-0 z-100 flex items-center justify-center bg-transparent">
                <img :src="activeImage" @click.stop class="max-w-[90vw] max-h-[90vh] object-contain">

                <button type="button" @click="open = false" class="btn btn-circle btn-sm absolute top-4 right-4 z-101">
                    ✕
                </button>
            </div>
        </template>
    </div>
@endif

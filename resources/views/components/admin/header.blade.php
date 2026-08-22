@props(['title', 'description' => null])

<header class="fixed top-0 right-0 left-60 z-30 h-14">
    <div class="h-full flex items-center justify-between px-6">
        <div>
            <h1 class="text-lg font-semibold text-muted-foreground font-koho">
                {{ $title }}
            </h1>

            @if ($description)
                <p class="text-sm text-muted-foreground font-koho">
                    {{ $description }}
                </p>
            @endif
        </div>

        {{-- Dynamic Right Side --}}
        <div class="flex items-center gap-3">
            <x-icons.notification />
            {{ $slot }}
        </div>

    </div>
</header>

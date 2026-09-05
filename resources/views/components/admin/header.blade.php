@props(['title', 'titleUrl' => null, 'description' => null, 'breadcrumbs' => []])

@php
    $hasBreadcrumbs = count($breadcrumbs) > 0;
@endphp

<header class="fixed top-0 right-0 left-60 z-30 h-16 bg-background">
    <div class="h-full flex items-center justify-between px-6">
        <div>
            <h1 class="text-lg font-semibold flex items-center gap-2">
                @if ($hasBreadcrumbs && $titleUrl)
                    <a href="{{ $titleUrl }}" class="text-muted-foreground hover:text-base-content transition-colors">
                        {{ $title }}
                    </a>
                @else
                    <span>{{ $title }}</span>
                @endif

                @foreach ($breadcrumbs as $crumb)
                    <div x-data="{ show: false }" x-init="show = true" x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-1"
                        x-transition:enter-end="opacity-100 translate-x-0" class="flex items-center gap-2">
                        <span class="text-muted-foreground/50">&gt;</span>

                        @if (!$loop->last && isset($crumb['url']))
                            <a href="{{ $crumb['url'] }}"
                                class="text-muted-foreground hover:text-base-content transition-colors">
                                {{ $crumb['label'] }}
                            </a>
                        @else
                            <span class="text-base-content font-semibold">{{ $crumb['label'] }}</span>
                        @endif
                    </div>
                @endforeach
            </h1>

            @if ($description)
                <p class="text-xs text-muted-foreground font-koho">
                    {{ $description }}
                </p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
    </div>
</header>

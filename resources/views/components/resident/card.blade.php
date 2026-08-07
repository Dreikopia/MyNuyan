@props(['href' => null, 'title', 'description' => null])

<x-dynamic-component :component="$href ? 'a' : 'div'" @if ($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'bg-secondary w-full card-lg rounded-xl flex items-center gap-4 p-4']) }}>
    <div class="shrink-0">
        {{ $icon ?? '' }}
    </div>
    <div>
        <h2 class="card-title text-md font-koho font-bold">{{ $title }}</h2>
        <p class="text-md font-koho">{{ $description }}</p>
    </div>
</x-dynamic-component>

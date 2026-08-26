@props([
    'href' => null,
    'type' => 'button',
])

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge([
        'class' => 'btn btn-sm btn-primary/10',
    ]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge([
        'class' => 'btn btn-sm btn-primary',
    ]) }}>
        {{ $slot }}
    </button>
@endif

@props([
    'href' => '#',
    'active' => false,
])

<a href="{{ $href }}"
    {{ $attributes->class([
        'text-md font-koho',
        'bg-primary rounded-sm' => $active,
        'text-muted-foreground hover:bg-primary hover:text-foreground rounded-sm' => !$active,
    ]) }}>
    {{ $slot }}
</a>

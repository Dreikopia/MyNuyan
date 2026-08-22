@props([
    'href' => '#',
    'active' => false,
])

<a href="{{ $href }}"
    {{ $attributes->class([
        'text-md font-koho',
        'bg-primary/10 rounded-sm' => $active,
        'text-muted-foreground hover:bg-primary/10 hover:text-foreground rounded-sm' => !$active,
    ]) }}>
    {{ $slot }}
</a>

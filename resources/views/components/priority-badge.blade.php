@props(['priority'])

@php
    $classes = match ($priority->value) {
        'low' => 'bg-green-500/10 text-green-500 border-green-500/20',
        'medium' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
        'high' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
        'urgent' => 'bg-red-500/10 text-red-500 border-red-500/20',
    };
@endphp

<span
    {{ $attributes->merge([
        'class' => "inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium {$classes}",
    ]) }}>
    {{ $priority->label() }}
</span>

@props(['route', 'pattern', 'icon', 'label'])

<a href="{{ route($route) }}"
    class="flex flex-col items-center justify-center gap-1
           {{ request()->routeIs($pattern) ? 'text-red-600' : 'text-gray-500' }}">
    <x-dynamic-component :component="'icons.' . $icon" />
    <span class="text-xs">{{ $label }}</span>
</a>

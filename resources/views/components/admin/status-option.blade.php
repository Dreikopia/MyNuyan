@props([
    'value',
    'title',
    'description',
    'color' => 'primary', // info | success | error | warning | primary
    'compact' => false, // stacked layout for 2-column grids
])

@php
    // Full class strings so Tailwind can see them.
    $s = match ($color) {
        'info' => [
            'hover' => 'hover:border-info/50 hover:bg-info/5',
            'active' => 'border-info bg-info/5 ring-2 ring-info/15',
            'icon' => 'bg-info/10 text-info',
            'check' => 'bg-info',
        ],
        'success' => [
            'hover' => 'hover:border-success/50 hover:bg-success/5',
            'active' => 'border-success bg-success/5 ring-2 ring-success/15',
            'icon' => 'bg-success/10 text-success',
            'check' => 'bg-success',
        ],
        'error' => [
            'hover' => 'hover:border-error/50 hover:bg-error/5',
            'active' => 'border-error bg-error/5 ring-2 ring-error/15',
            'icon' => 'bg-error/10 text-error',
            'check' => 'bg-error',
        ],
        'warning' => [
            'hover' => 'hover:border-warning/50 hover:bg-warning/5',
            'active' => 'border-warning bg-warning/5 ring-2 ring-warning/15',
            'icon' => 'bg-warning/10 text-warning',
            'check' => 'bg-warning',
        ],
        default => [
            'hover' => 'hover:border-primary/50 hover:bg-primary/5',
            'active' => 'border-primary bg-primary/5 ring-2 ring-primary/15',
            'icon' => 'bg-primary/10 text-primary',
            'check' => 'bg-primary',
        ],
    };
@endphp

<label
    class="group relative flex cursor-pointer select-none rounded-2xl border bg-base-100 p-3 transition {{ $compact ? 'flex-col items-start gap-2' : 'items-center gap-3' }} {{ $s['hover'] }}"
    :class="selectedStatus === '{{ $value }}' ? '{{ $s['active'] }}' : 'border-base-300/60'"
    @click.prevent="selectedStatus = selectedStatus === '{{ $value }}' ? null : '{{ $value }}'">

    <input type="radio" name="status" value="{{ $value }}" x-model="selectedStatus" class="sr-only">

    {{-- Icon --}}
    <div class="flex size-9 shrink-0 items-center justify-center rounded-xl {{ $s['icon'] }}">
        {{ $slot }}
    </div>

    {{-- Text --}}
    <div class="min-w-0 pr-5">
        <p class="text-sm font-semibold leading-tight text-base-content">{{ $title }}</p>
        <p class="mt-0.5 truncate text-xs text-base-content/50">{{ $description }}</p>
    </div>

    {{-- Selected check --}}
    <span x-show="selectedStatus === '{{ $value }}'" x-cloak
        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="scale-50 opacity-0"
        x-transition:enter-end="scale-100 opacity-100"
        class="absolute right-2.5 top-2.5 flex size-4 items-center justify-center rounded-full text-white {{ $s['check'] }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5" />
        </svg>
    </span>
</label>

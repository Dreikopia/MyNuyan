@props(['complaint'])

@php

    $initials = strtoupper(
        mb_substr($complaint->user->first_name, 0, 1) . mb_substr($complaint->user->last_name, 0, 1),
    );
@endphp

<div class="flex flex-col gap-4">

    {{-- Header --}}
    <header class="space-y-1.5">
        <div class="flex items-center gap-2 text-[11px] font-medium uppercase tracking-wide text-base-content/50">
            <span>Complaint</span>
            <span class="text-base-content/30">•</span>
            <span class="font-mono normal-case">#{{ str_pad($complaint->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="flex items-start justify-between gap-4">
            <h3 class="text-xl font-semibold tracking-tight leading-tight text-base-content">
                {{ $complaint->category->name }}
            </h3>
            <x-status-badge :status="$complaint->status" class="shrink-0" />

        </div>
    </header>

    {{-- Reporter --}}
    <section class="flex items-center gap-3 rounded-2xl border border-base-300/60 bg-base-100 p-3">
        <div
            class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">
            {{ $initials }}
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[11px] font-medium uppercase tracking-wide text-base-content/50">Reported by</p>
            <p class="truncate text-sm font-semibold text-base-content">
                {{ $complaint->user->first_name }} {{ $complaint->user->last_name }}
            </p>
        </div>
        @if ($complaint->user->phone_number)
            <a href="tel:{{ $complaint->user->phone_number }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-base-300/60 px-2.5 py-1.5 text-xs font-medium text-base-content transition hover:border-primary/40 hover:bg-primary/5 hover:text-primary">
                <x-icons.phone class="size-3.5 shrink-0" />
                {{ $complaint->user->phone_number }}
            </a>
        @else
            <span class="text-xs text-base-content/40">No phone</span>
        @endif
    </section>

    {{-- Meta — aligned grid, no lines --}}
    <dl class="grid grid-cols-[1rem_4.5rem_1fr] items-start gap-x-3 gap-y-2 text-sm">
        <x-icons.date class="mt-0.5 size-4 text-base-content/40" />
        <dt class="text-base-content/50">Date</dt>
        <dd class="min-w-0 font-medium text-base-content">
            {{ $complaint->created_at->format('M d, Y') }}
            <span class="font-normal text-base-content/40">· {{ $complaint->created_at->format('h:i A') }}</span>
        </dd>

        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 size-4 text-base-content/40">
            <path
                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
            <circle cx="12" cy="10" r="3" />
        </svg>
        <dt class="text-base-content/50">Location</dt>
        <dd class="min-w-0 font-medium leading-snug text-base-content wrap-break-words">
            {{ $complaint->location }}
        </dd>
    </dl>

    <section class="mt-2">
        <h4 class="text-[11px] font-medium tracking-wide text-base-content/50 mb-2">Description</h4>
        <p>
            {{ $complaint->description }}
        </p>
    </section>

</div>

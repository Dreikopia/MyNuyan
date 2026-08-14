@props(['complaint'])

<div class="space-y-3">
    {{-- Title: bold, combines category + location into one line --}}
    <h3 class="font-bold text-base">
        {{ $complaint->category->name }} at {{ $complaint->location }}
    </h3>

    {{-- Description: sits outside any card, muted color so it doesn't compete with the title --}}
    <p class="text-sm leading-relaxed text-muted-foreground">
        {{ $complaint->description }}
    </p>

    <div class="flex space-x-2 w-full">
        <div class="bg-surface rounded-lg p-4 space-y-3 text-sm">
            <p class="text-xs tracking-wide text-base-content/50">Reported by</p>
            <p class="font-medium">{{ $complaint->user->first_name }}</p>
        </div>

        <div class="bg-surface rounded-lg p-4 space-y-3 text-sm">
            <p class="text-xs tracking-wide text-base-content/50">Category</p>
            <p class="font-medium">{{ $complaint->category->name }}</p>
        </div>

        <div class="bg-surface rounded-lg p-4 space-y-3 text-sm">
            <p class="text-xs tracking-wide text-base-content/50">Location</p>
            <p class="font-medium">{{ $complaint->location }}</p>
        </div>

        <div class="bg-surface rounded-lg p-4 space-y-3 text-sm">
            <p class="text-xs tracking-wide text-base-content/50">Date Filed</p>
            <p class="font-medium">{{ $complaint->created_at->format('M d, Y - h:i A') }}</p>
        </div>
    </div>

</div>

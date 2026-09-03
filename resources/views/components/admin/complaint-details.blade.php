@props(['complaint'])

<div class="space-y-4">

    <div class="min-w-0">
        <h3 class="font-bold text-base">
            {{ $complaint->category->name }}
        </h3>

        <div class="flex items-center gap-1.5 text-sm text-base-content/70 mt-1 space-x-2">

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" class="size-4 shrink-0 text-base-content/40">
                <path d="M8 2v4" />
                <path d="M16 2v4" />
                <rect width="18" height="18" x="3" y="4" rx="2" />
                <path d="M3 10h18" />
            </svg>
            <span class="font-medium">{{ $complaint->created_at->format('M d, Y h:i A') }}</span>

            <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm">
                {{-- Reported by --}}
                <div class="flex items-center gap-1.5 min-w-0 text-base-content/70">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="size-4 shrink-0 text-base-content/40">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span class="font-medium wrap-break-word">{{ $complaint->user->first_name }}
                        {{ $complaint->user->last_name }}</span>
                </div>

                {{-- Phone number --}}
                <div class="flex items-center gap-1.5 min-w-0 text-base-content/70">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="size-4 shrink-0 text-base-content/40">
                        <path
                            d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                    </svg>
                    <span class="font-medium wrap-break-word">{{ $complaint->user->phone_number ?? '—' }}</span>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <p class="text-sm text-muted-foreground mb-1">Description</p>
            <div class="bg-background rounded-lg p-3 text-sm leading-relaxed text-muted-foreground wrap-break-word">
                {{ $complaint->description }}
            </div>
        </div>



        {{-- Location --}}
        <div class="flex items-center gap-1.5 min-w-0 basis-full text-base-content/70">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="size-4 shrink-0 text-base-content/40">
                <path
                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                <circle cx="12" cy="10" r="3" />
            </svg>
            <span class="font-medium wrap-break-word">{{ $complaint->location }}</span>
        </div>
    </div>
</div>

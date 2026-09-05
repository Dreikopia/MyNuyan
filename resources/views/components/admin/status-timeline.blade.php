@props(['histories'])

@php
    $statusLabels = [
        'submitted' => 'Submitted',
        'under_review' => 'Under Review',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',
        'rejected' => 'Rejected',
    ];
@endphp

<ul class="timeline timeline-vertical timeline-compact timeline-snap-icon">

    @forelse ($histories as $history)
        @php
            $label = $statusLabels[$history->status->value] ?? ucfirst($history->status->value);
        @endphp

        <li>

            @if (!$loop->first)
                <hr class="bg-primary w-px!" />
            @endif

            <div class="timeline-middle">
                <span class="block size-2 rounded-full bg-primary"></span>
            </div>

            <div class="timeline-end pb-5 pl-1">
                <p class="text-sm font-semibold leading-none">
                    {{ $label }}
                </p>

                <p class="text-xs text-base-content/60 mt-1.5">
                    {{ $history->changedBy?->username ?? 'System' }}
                    · {{ $history->created_at->format('M d, Y - h:i A') }}
                </p>

                @if ($history->remarks)
                    <p class="text-sm mt-1.5">
                        {{ $history->remarks }}
                    </p>
                @endif
            </div>

            @if (!$loop->last)
                <hr class="bg-primary w-px!" />
            @endif

        </li>

    @empty

        <li class="text-sm text-base-content/50">
            No status history yet.
        </li>
    @endforelse

</ul>

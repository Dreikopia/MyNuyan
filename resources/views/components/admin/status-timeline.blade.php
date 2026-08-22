@props(['histories'])

@php
    $statusLabels = [
        'submitted' => 'Submitted',
        'under_review' => 'Under Review',
        'in_progress' => 'In Progress',
        'pending_confirmation' => 'Pending Confirmation',
        'resolved' => 'Resolved',
        'rejected' => 'Rejected',
    ];
@endphp

<ul class="timeline timeline-vertical timeline-compact">

    @forelse ($histories as $history)
        @php
            $label = $statusLabels[$history->status->value] ?? ucfirst($history->status->value);
        @endphp

        <li>

            @if (!$loop->first)
                <hr class="bg-primary" />
            @endif

            <div class="timeline-middle">
                <span class="inline-block h-4 w-4 rounded-full bg-primary ring ring-base-100"></span>
            </div>

            <div class="timeline-end timeline-box">
                <p class="font-semibold text-sm">
                    {{ $label }}
                </p>

                <p class="text-xs text-base-content/60 mt-0.5">
                    {{ $history->changedBy?->username ?? 'System' }}
                    · {{ $history->created_at->format('M d, Y - h:i A') }}
                </p>

                @if ($history->remarks)
                    <p class="text-sm mt-1">
                        {{ $history->remarks }}
                    </p>
                @endif
            </div>

            @if (!$loop->last)
                <hr class="bg-primary" />
            @endif

        </li>

    @empty

        <li class="text-sm text-base-content/50 pl-2">
            No status history yet.
        </li>
    @endforelse
</ul>

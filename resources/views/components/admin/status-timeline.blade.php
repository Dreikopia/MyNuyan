@props(['histories'])

@php
    $statusMeta = [
        'submitted' => ['label' => 'Submitted', 'color' => 'bg-neutral'],
        'under_review' => ['label' => 'Under Review', 'color' => 'bg-info'],
        'in_progress' => ['label' => 'In Progress', 'color' => 'bg-primary'],
        'pending_confirmation' => ['label' => 'Pending Confirmation', 'color' => 'bg-warning'],
        'resolved' => ['label' => 'Resolved', 'color' => 'bg-success'],
        'rejected' => ['label' => 'Rejected', 'color' => 'bg-error'],
    ];
@endphp

<ul class="timeline timeline-vertical timeline-compact">
    @forelse ($histories as $history)
        @php
            $meta = $statusMeta[$history->status->value] ?? [
                'label' => ucfirst($history->status->value),
                'color' => 'bg-base-300',
            ];
        @endphp

        <li>
            @if (!$loop->first)
                <hr class="{{ $meta['color'] }}" />
            @endif

            <div class="timeline-middle">
                <span class="inline-block h-4 w-4 rounded-full {{ $meta['color'] }} ring ring-base-100"></span>
            </div>

            <div class="timeline-end timeline-box">
                <p class="font-semibold text-sm">{{ $meta['label'] }}</p>
                <p class="text-xs text-base-content/60 mt-0.5">
                    {{ $history->changedBy?->username ?? 'System' }}
                    · {{ $history->created_at->format('M d, Y - h:i A') }}
                </p>
                @if ($history->remarks)
                    <p class="text-sm mt-1">{{ $history->remarks }}</p>
                @endif
            </div>

            {{-- Line below this dot (skip on the very last / oldest item) --}}
            @if (!$loop->last)
                <hr class="{{ $meta['color'] }}" />
            @endif
        </li>
    @empty
        <li class="text-sm text-base-content/50 pl-2">No status history yet.</li>
    @endforelse
</ul>

@props(['status'])

@php
    $value = $status instanceof \App\Enums\ComplaintStatus ? $status->value : $status;

    $label = match ($value) {
        'submitted' => 'Submitted',
        'under_review' => 'Under Review',
        'in_progress' => 'In Progress',
        'pending_confirmation' => 'Pending Confirmation',
        'resolved' => 'Resolved',
        'rejected' => 'Rejected',
        default => ucfirst(str_replace('_', ' ', $value)),
    };

    [$pill, $dot] = match ($value) {
        'submitted' => ['bg-primary/10 text-primary border-primary/30', 'bg-primary'],
        'under_review' => ['bg-info/10 text-info border-info/30', 'bg-info'],
        'in_progress', 'pending_confirmation' => ['bg-warning/10 text-warning border-warning/30', 'bg-warning'],
        'resolved' => ['bg-success/10 text-success border-success/30', 'bg-success'],
        'rejected' => ['bg-error/10 text-error border-error/30', 'bg-error'],
        default => ['bg-base-200 text-base-content/70 border-base-300', 'bg-base-content/40'],
    };
@endphp

<span
    {{ $attributes->merge([
        'class' => "inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border px-2.5 py-1 text-xs font-medium $pill",
    ]) }}>
    <span class="size-1.5 shrink-0 rounded-full {{ $dot }}"></span>
    {{ $label }}
</span>

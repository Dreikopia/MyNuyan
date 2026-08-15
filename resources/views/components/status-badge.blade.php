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

    $baseClasses = 'badge badge-sm font-medium whitespace-nowrap rounded-2xl';

    $statusClasses = match ($value) {
        'submitted' => 'badge-primary',
        'under_review' => 'badge-info',
        'in_progress' => 'badge-bg-amber',
        'pending_confirmation' => 'badge-warning',
        'resolved' => 'badge-success',
        'rejected' => 'badge-error',
        default => 'badge-ghost',
    };

    $classes = "$baseClasses $statusClasses";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $label }}
</span>

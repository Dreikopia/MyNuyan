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

    $baseClasses = 'badge badge-soft badge badge badge-soft badge-sm font-medium whitespace-nowrap rounded-2xl';

    $statusClasses = match ($value) {
        'submitted' => ' badge badge-soft badge-primary',
        'under_review' => 'badge badge-soft badge-info',
        'in_progress' => 'badge badge-soft badge-warning',
        'pending_confirmation' => 'badge badge-soft badge-warning',
        'resolved' => 'badge badge-soft badge-success',
        'rejected' => 'badge badge-soft badge-error',
        default => 'badge badge-soft badge-ghost',
    };

    $classes = "$baseClasses $statusClasses";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $label }}
</span>

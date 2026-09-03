<div
    class="alert text-foreground"
    :class="{
        'alert-info bg-info/10': selectedStatus === 'under_review',
        'alert-error bg-error/10': selectedStatus === 'rejected',
        'alert-success bg-success/10':
            selectedStatus === 'in_progress' || selectedStatus === 'resolved',
    }"
>
    <span x-show="selectedStatus === 'under_review'">
        Complaint will be marked as Under Review.
    </span>


<span x-show="selectedStatus === 'in_progress'">
    Complaint will be approved and moved to In Progress.
</span>

<span x-show="selectedStatus === 'rejected'">
    This complaint will be rejected.
</span>

<span x-show="selectedStatus === 'resolved'">
    Complaint will be marked as Resolved.
</span>


</div>

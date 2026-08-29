<div x-show="selectedStatus !== null" x-transition class="alert mt-2 text-foreground font-bold" :class="selectedStatus === 'rejected' ? 'alert-error bg-error/5' : 'alert-success bg-success/5'">
    <span x-show="selectedStatus === 'under_review'">Complaint will be marked as
        Under Review.</span>
    <span x-show="selectedStatus === 'in_progress'">Complaint will be approved and
        moved to In Progress.</span>
    <span x-show="selectedStatus === 'rejected'">This complaint will be
        rejected.</span>
    <span x-show="selectedStatus === 'resolved'">Complaint will be marked as
        Resolved.</span>
</div>

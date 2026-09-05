<div class="flex items-start gap-2.5 rounded-2xl border px-3.5 py-2.5 text-sm text-base-content/80"
     :class="{
         'border-info/30 bg-info/10':       selectedStatus === 'under_review',
         'border-error/30 bg-error/10':     selectedStatus === 'rejected',
         'border-success/30 bg-success/10': selectedStatus === 'in_progress' || selectedStatus === 'resolved',
     }">

    {{-- Icon --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 size-4 shrink-0"
         :class="{
             'text-info':    selectedStatus === 'under_review',
             'text-error':   selectedStatus === 'rejected',
             'text-success': selectedStatus === 'in_progress' || selectedStatus === 'resolved',
         }"
         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
    </svg>

    <p class="leading-snug">
        <span x-show="selectedStatus === 'under_review'">Complaint will be marked as <strong class="font-semibold">Under Review</strong>.</span>
        <span x-show="selectedStatus === 'in_progress'">Complaint will be approved and moved to <strong class="font-semibold">In Progress</strong>.</span>
        <span x-show="selectedStatus === 'rejected'">This complaint will be <strong class="font-semibold">rejected</strong>.</span>
        <span x-show="selectedStatus === 'resolved'">Complaint will be marked as <strong class="font-semibold">Resolved</strong> and moved to the archive.</span>
    </p>
</div>
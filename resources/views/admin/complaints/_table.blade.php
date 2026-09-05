@php
    use App\Enums\ComplaintStatus;
    use App\Enums\ComplaintPriority;
@endphp

<table class="table table-md bg-surface">
    <thead class="sticky top-0 z-10 text-base-content/70 uppercase text-[11px] tracking-wide bg-gray-700">
        <tr>
            <th>Id</th>
            <th>Category</th>
            <th>Complainant</th>
            <th>Created</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody class="text-xs divide-y">
        @forelse ($complaints as $complaint)
            @php
                $isFinal = in_array($complaint->status, [ComplaintStatus::RESOLVED, ComplaintStatus::REJECTED], true);
            @endphp
            <tr class="hover:bg-base-200/60 transition-colors">
                <td class="font-medium">
                    {{ $complaint->complaint_id }}
                </td>
                <td class="max-w-50">
                    <p>{{ $complaint->category->name }}</p>
                    <p class="text-xs text-muted-foreground line-clamp-1">{{ $complaint->description }}</p>

                </td>
                <td class="max-w-30">
                    <p class="line-clamp-1 text-xs">
                    <p>{{ $complaint->user->first_name }}
                </td>

                <td class="whitespace-nowrap text-base-content/70">
                    {{ $complaint->created_at->diffForHumans() }}
                </td>
                <td class="px-4 py-3">
                    @if ($isFinal)
                        <x-priority-badge :priority="$complaint->priority" />
                    @else
                        @php
                            $priorityClasses = match ($complaint->priority->value) {
                                'low' => 'bg-green-500/10 text-green-500 border-green-500/20',
                                'medium' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                'high' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                                'urgent' => 'bg-red-500/10 text-red-500 border-red-500/20',
                            };
                        @endphp

                        <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
                            @csrf
                            @method('PATCH')

                            <div class="relative inline-block">
                                <select name="priority" onchange="this.form.submit()"
                                    class="appearance-none cursor-pointer inline-flex items-center rounded-full border pl-2.5 pr-5 py-1 text-xs font-medium {{ $priorityClasses }}">
                                    @foreach (ComplaintPriority::cases() as $priority)
                                        <option value="{{ $priority->value }}" @selected($complaint->priority->value === $priority->value)>
                                            {{ $priority->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-1.5 top-1/2 h-2.5 w-2.5 -translate-y-1/2"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                                </svg>
                            </div>

                        </form>
                    @endif
                </td>
                <td class="max-w-30">
                    <x-status-badge :status="$complaint->status" />
                </td>

                <td>

                    <label for="complaint-drawer-{{ $complaint->id }}" class="cursor-pointer">
                        <span class="btn btn-xs btn-outline">
                            Review
                        </span>
                    </label>


                    <x-admin.drawer id="complaint-drawer-{{ $complaint->id }}">
                        <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}"
                            class="flex flex-col flex-1 overflow-hidden" x-data="{ selectedStatus: null }">
                            @csrf
                            @method('PATCH')

                            <div class="relative flex-1 overflow-y-auto px-6 pt-4 pb-24 space-y-5">

                                <x-admin.complaints-details :complaint="$complaint" />

                                <x-admin.complaints-images :complaint="$complaint" />

                                @if ($isFinal)
                                    {{-- Closed notice --}}
                                    <div
                                        class="flex items-center gap-3 rounded-2xl border border-dashed border-base-300 bg-base-200/40 px-4 py-3">
                                        <div
                                            class="flex size-8 shrink-0 items-center justify-center rounded-full bg-base-300/60 text-base-content/60">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect width="18" height="11" x="3" y="11" rx="2" />
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-base-content">This complaint is closed
                                            </p>
                                            <p class="text-xs text-base-content/50">
                                                Marked as {{ $complaint->status->label() }} — no further updates can be
                                                made.
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <section class="space-y-2">
                                        <div class="flex items-center gap-3">
                                            <h4
                                                class="text-[11px] font-medium uppercase tracking-wide text-base-content/50">
                                                Remarks
                                            </h4>
                                            <div class="h-px flex-1 bg-base-300/60"></div>
                                        </div>

                                        <x-field name="remarks" type="textarea" :value="$complaint->remarks" rows="3"
                                            placeholder="Add a note for the reporter or your team…"
                                            class="textarea w-full rounded-2xl border-base-300/60 bg-base-100 text-sm leading-relaxed placeholder:text-base-content/40 focus:border-primary/50 focus:outline-none focus:ring-2 focus:ring-primary/15" />
                                    </section>

                                    <section class="space-y-2">
                                        <div class="flex items-center gap-3">
                                            <h4
                                                class="text-[11px] font-medium uppercase tracking-wide text-base-content/50">
                                                Update status
                                            </h4>
                                            <div class="h-px flex-1 bg-base-300/60"></div>
                                            <span class="text-[11px] text-base-content/40">required to save</span>
                                        </div>

                                        @if ($complaint->status === ComplaintStatus::SUBMITTED)
                                            <x-admin.status-option value="under_review" color="info"
                                                title="Mark as Under Review"
                                                description="Start reviewing this complaint">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="11" cy="11" r="7" />
                                                    <path d="m21 21-4.3-4.3" />
                                                </svg>
                                            </x-admin.status-option>
                                        @elseif ($complaint->status === ComplaintStatus::UNDER_REVIEW)
                                            <div class="grid grid-cols-2 gap-2">

                                                <x-admin.status-option value="rejected" color="error" compact
                                                    title="Reject" description="Not a valid complaint">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 6 18M6 6l12 12" />
                                                    </svg>
                                                </x-admin.status-option>

                                                <x-admin.status-option value="in_progress" color="success" compact
                                                    title="Approve" description="Move to In Progress">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M20 6 9 17l-5-5" />
                                                    </svg>
                                                </x-admin.status-option>
                                            </div>
                                        @elseif ($complaint->status === ComplaintStatus::IN_PROGRESS)
                                            <x-admin.status-option value="resolved" color="success"
                                                title="Mark as Resolved"
                                                description="This complaint has been addressed">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </x-admin.status-option>
                                        @endif

                                        {{-- Banner (collapses when nothing selected) --}}
                                        <div class="grid transition-all duration-300 ease-out" :class="selectedStatus
                                            ? 'grid-rows-[1fr] opacity-100'
                                            : 'grid-rows-[0fr] opacity-0'">
                                            <div class="overflow-hidden">
                                                <x-admin.status-banner />
                                            </div>
                                        </div>
                                    </section>
                                @endif

                                @if ($complaint->statusHistories->isNotEmpty())
                                    <div class="pb-2">
                                        <h4 class="text-sm font-semibold text-base-content/70 mb-3">
                                            Status History
                                        </h4>
                                        <x-admin.status-timeline :histories="$complaint->statusHistories" />
                                    </div>
                                @endif

                            </div>

                            {{-- ── Sticky footer ── --}}
                            @unless ($isFinal)
                                <div
                                    class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-3 border-t border-base-300/60 bg-base-100/90 px-6 py-3 backdrop-blur">
                                    <p class="text-xs text-base-content/50"
                                        x-text="selectedStatus ? 'Status will change on save.' : 'Select a status to save changes.'">
                                    </p>
                                    <button type="submit" class="btn btn-primary btn-sm rounded-xl px-4"
                                        :disabled="!selectedStatus" :class="!selectedStatus && 'btn-disabled opacity-60 cursor-not-allowed'">
                                        Confirm update
                                    </button>
                                </div>
                            @endunless
                        </form>
                    </x-admin.drawer>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="15" class="py-12">
                    <div class="flex flex-col items-center justify-center text-center gap-1">
                        <p class="text-base-content/60">No complaints found.</p>
                        <p class="text-sm text-base-content/40">Try adjusting your filters or search.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>

</table>

</div>

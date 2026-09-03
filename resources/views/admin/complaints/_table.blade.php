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


                    <div class="drawer drawer-end">
                        <input id="complaint-drawer-{{ $complaint->id }}" type="checkbox" class="drawer-toggle" />

                        <div class="drawer-side z-50">
                            <label for="complaint-drawer-{{ $complaint->id }}" class="drawer-overlay"></label>

                            <div class="bg-surface h-full w-full max-w-xl lg:max-w-2xl flex flex-col">
                                <div class="flex items-center justify-between p-6 pb-3">

                                    <div class="flex items-center gap-2">
                                        <label for="complaint-drawer-{{ $complaint->id }}" class="cursor-pointer">
                                            <x-icons.panel-right />
                                        </label>
                                        <h3 class="text-lg font-bold">{{ $complaint->complaint_id }}</h3>
                                    </div>
                                    <x-status-badge :status="$complaint->status" />
                                </div>


                                <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}"
                                    class="flex flex-col flex-1 overflow-hidden" x-data="{ selectedStatus: null }">
                                    @csrf
                                    @method('PATCH')

                                    <div class="flex-1 overflow-y-auto px-6 pt-4 space-y-5">

                                        <x-admin.complaint-details :complaint="$complaint" />

                                        <x-admin.complaints-images :complaint="$complaint" />

                                        @unless ($isFinal)
                                            <x-field name="remarks" type="textarea" :value="$complaint->remarks"
                                                class="textarea textarea-bordered w-full rows-4"
                                                placeholder="Send a remarks" label="Remarks" />
                                        @endunless

                                        <div class="space-y-3">
                                            @if ($complaint->status !== ComplaintStatus::RESOLVED && $complaint->status !== ComplaintStatus::REJECTED)
                                                <label class="label">
                                                    <span class="text-xs">Update Status</span>
                                                </label>
                                            @endif

                                            @if ($complaint->status === ComplaintStatus::SUBMITTED)
                                                <label
                                                    class="flex items-center gap-2 card border-2 cursor-pointer p-2.5 transition-all duration-200 hover:border-info/60 hover:bg-info/5"
                                                    :class="selectedStatus === 'under_review' ? 'border-info bg-info/5' : 'border-base-300'"
                                                    @click.prevent="selectedStatus = selectedStatus === 'under_review' ? null : 'under_review'">
                                                    <input type="radio" name="status" value="under_review"
                                                        x-model="selectedStatus" class="sr-only">
                                                    <div
                                                        class="flex items-center justify-center size-7 rounded-full bg-info/10 text-info shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <circle cx="11" cy="11" r="7" />
                                                            <path d="m21 21-4.3-4.3" />
                                                        </svg>
                                                    </div>
                                                    <div class="min-w-0 text-left">
                                                        <p class="font-semibold text-xs">Mark as Under Review</p>
                                                        <p class="text-[11px] text-muted-foreground truncate">Start
                                                            reviewing this complaint</p>
                                                    </div>
                                                </label>
                                            @elseif ($complaint->status === ComplaintStatus::UNDER_REVIEW)
                                                <div class="grid grid-cols-2 gap-2">
                                                    <label
                                                        class="flex flex-col items-start text-left gap-1.5 card border-2 cursor-pointer p-2.5 transition-all duration-200 hover:border-error/60 hover:bg-error/5"
                                                        :class="selectedStatus === 'rejected' ? 'border-error bg-error/5' : 'border-base-300'"
                                                        @click.prevent="selectedStatus = selectedStatus === 'rejected' ? null : 'rejected'">
                                                        <input type="radio" name="status" value="rejected"
                                                            x-model="selectedStatus" class="sr-only">
                                                        <div
                                                            class="flex items-center justify-center size-7 rounded-full bg-error/10 text-error shrink-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2">
                                                                <path d="M18 6 6 18M6 6l12 12" />
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0 text-left">
                                                            <p class="font-semibold text-xs">Reject</p>
                                                            <p class="text-[11px] text-muted-foreground truncate">
                                                                Not a valid complaint</p>
                                                        </div>
                                                    </label>

                                                    <label
                                                        class="flex flex-col items-start text-left gap-1.5 card border-2 cursor-pointer p-2.5 transition-all duration-200 hover:border-success/60 hover:bg-success/5"
                                                        :class="selectedStatus === 'in_progress' ? 'border-success bg-success/5' : 'border-base-300'"
                                                        @click.prevent="selectedStatus = selectedStatus === 'in_progress' ? null : 'in_progress'">
                                                        <input type="radio" name="status" value="in_progress"
                                                            x-model="selectedStatus" class="sr-only">
                                                        <div
                                                            class="flex items-center justify-center size-7 rounded-full bg-success/10 text-success shrink-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5"
                                                                viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2">
                                                                <path d="M20 6 9 17l-5-5" />
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0 text-left">
                                                            <p class="font-semibold text-xs">Approve</p>
                                                            <p class="text-[11px] text-muted-foreground truncate">
                                                                Move to In Progress</p>
                                                        </div>
                                                    </label>
                                                </div>
                                            @elseif ($complaint->status === ComplaintStatus::IN_PROGRESS)
                                                <label
                                                    class="flex items-center gap-2 card border-2 cursor-pointer p-2.5 transition-all duration-200 hover:border-success/60 hover:bg-success/5"
                                                    :class="selectedStatus === 'resolved' ? 'border-success bg-success/5' : 'border-base-300'"
                                                    @click.prevent="selectedStatus = selectedStatus === 'resolved' ? null : 'resolved'">
                                                    <input type="radio" name="status" value="resolved"
                                                        x-model="selectedStatus" class="sr-only">
                                                    <div
                                                        class="flex items-center justify-center size-7 rounded-full bg-success/10 text-success shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path
                                                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                        </svg>
                                                    </div>
                                                    <div class="min-w-0 text-left">
                                                        <p class="font-semibold text-xs">Mark as Resolved</p>
                                                        <p class="text-[11px] text-muted-foreground truncate">This
                                                            complaint has been addressed</p>
                                                    </div>
                                                </label>
                                            @endif

                                            <div class="grid transition-all duration-300 ease-out" :class="selectedStatus
                                                ? 'grid-rows-[1fr] opacity-100 mt-2'
                                                : 'grid-rows-[0fr] opacity-0 mt-0'">
                                                <div class="overflow-hidden">
                                                    <x-admin.status-banner />
                                                </div>
                                            </div>
                                        </div>

                                        @if ($complaint->statusHistories->isNotEmpty())
                                            <div class="pb-2">
                                                <h4 class="text-sm font-semibold text-base-content/70 mb-3">
                                                    Status History
                                                </h4>
                                                <x-admin.status-timeline :histories="$complaint->statusHistories" />
                                            </div>
                                        @endif
                                    </div>

                                    <div
                                        class="sticky bottom-0 z-10 flex w-full items-center justify-end gap-2 border-t border-base-300 bg-base-100 p-6 pt-4">


                                        <button type="submit" class="btn flex-1" :class="selectedStatus ? 'btn-primary' : 'btn-disabled'"
                                            :disabled="!selectedStatus">
                                            Save changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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

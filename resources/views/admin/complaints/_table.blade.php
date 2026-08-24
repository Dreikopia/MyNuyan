@php
    use App\Enums\ComplaintStatus;
    use App\Enums\ComplaintPriority;
@endphp

<table class="table table-md bg-background">
    <thead class="sticky top-0 z-10 bg-surface text-base-content/70 uppercase text-[11px] tracking-wide">
        <tr>
            <th>Id</th>
            <th>Category</th>
            <th>Location</th>
            <th>Date</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody class="text-xs divide-y divide-base-300">
        @forelse ($complaints as $complaint)
            @php
                $isFinal = in_array($complaint->status, [ComplaintStatus::RESOLVED, ComplaintStatus::REJECTED], true);
            @endphp
            <tr class="hover:bg-base-200/60 transition-colors">
                <td class="font-medium">
                    {{ $complaint->complaint_id }}
                </td>
                <td class="max-w-50">
                    <p class="font-bold"> {{ $complaint->category->name }}</p>
                    <span class="text-xs text-muted-foreground line-clamp-1">
                        {{ $complaint->description }}
                    </span>
                </td>
                <td class="max-w-30">
                    <p class="line-clamp-1 text-xs">
                        {{ $complaint->location }}
                    </p>
                </td>

                <td class="whitespace-nowrap text-base-content/70">
                    {{ $complaint->created_at->diffForHumans() }}
                </td>
                <td class="px-4 py-3">
                    <x-priority-badge :priority="$complaint->priority" />
                </td>
                <td class="max-w-40">
                    <x-status-badge :status="$complaint->status" />
                </td>

                {{-- ...everything from here down is identical to what you already had... --}}
                <td>
                    @if (!$isFinal)
                        <label for="complaint-drawer-{{ $complaint->id }}" class="btn btn-sm bg-primary/50">
                            Review
                        </label>
                    @else
                        <div class="flex items-center gap-6">
                            <label for="complaint-drawer-{{ $complaint->id }}" class="btn btn-sm bg-primary/50">
                                Review
                            </label>
                            <span>
                                <x-icons.archived />
                            </span>
                        </div>
                    @endif
                </td>
                <div class="drawer drawer-end">
                    {{-- THE SWITCH: this checkbox's checked/unchecked state IS the open/close state --}}
                    <input id="complaint-drawer-{{ $complaint->id }}" type="checkbox" class="drawer-toggle" />

                    <div class="drawer-side z-50">
                        <label for="complaint-drawer-{{ $complaint->id }}" aria-label="close sidebar"
                            class="drawer-overlay"></label>

                        <div class="bg-base-100 min-h-full w-full max-w-xl lg:max-w-2xl flex flex-col">

                            <div class="flex items-center justify-between border-b border-base-300 p-6 pb-3">
                                <div>
                                    <h3 class="text-lg font-bold">
                                        {{ $complaint->complaint_id }}
                                    </h3>

                                    </p>
                                </div>
                                <x-status-badge :status="$complaint->status" />
                            </div>

                            <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}"
                                class="flex flex-col flex-1 overflow-hidden">
                                @csrf
                                @method('PATCH')

                                {{-- SCROLLABLE AREA: only this part scrolls if content is long, header/footer stay put --}}
                                <div class="flex-1 overflow-y-auto px-6 space-y-4">

                                    <x-admin.complaint-details :complaint="$complaint" />

                                    <x-admin.complaints-images :complaint="$complaint" />

                                    @unless ($isFinal)
                                        <x-field name="remarks" type="textarea" :value="$complaint->remarks"
                                            class="textarea textarea-bordered w-full rows-4" placeholder="Send a remarks"
                                            label="Remarks" />

                                        <div>
                                            <label for="priority-{{ $complaint->id }}" class="label">Priority</label>
                                            <select name="priority" id="priority-{{ $complaint->id }}"
                                                class="select select-bordered w-full">
                                                @foreach (ComplaintPriority::cases() as $priority)
                                                    <option value="{{ $priority->value }}" @selected(old('priority', $complaint->priority->value) === $priority->value)>
                                                        {{ $priority->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endunless

                                    <div x-data="{ selectedStatus: null }" class="space-y-2">
                                        @if ($complaint->status !== ComplaintStatus::RESOLVED && $complaint->status !== ComplaintStatus::REJECTED)
                                            <label class="label">
                                                <span class="label-text font-medium">Update Status</span>
                                            </label>
                                        @endif

                                        @if ($complaint->status === ComplaintStatus::SUBMITTED)
                                            <label
                                                class="card border cursor-pointer p-4 hover:border-primary transition"
                                                :class="selectedStatus === 'under_review' ? 'border-primary bg-primary/5' : 'border-base-300'"
                                                @click.prevent="selectedStatus = selectedStatus === 'under_review' ? null : 'under_review'">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="status" value="under_review"
                                                        x-model="selectedStatus" class="radio radio-primary">
                                                    <span class="font-medium">Mark as Under Review</span>
                                                </div>
                                            </label>
                                        @elseif ($complaint->status === ComplaintStatus::UNDER_REVIEW)
                                            <div class="grid grid-cols-2 gap-3">
                                                <label
                                                    class="card border cursor-pointer p-4 hover:border-error transition"
                                                    :class="selectedStatus === 'rejected' ? 'border-error bg-error/5' : 'border-base-300'"
                                                    @click.prevent="selectedStatus = selectedStatus === 'rejected' ? null : 'rejected'">
                                                    <div class="flex items-center gap-3">
                                                        <input type="radio" name="status" value="rejected"
                                                            x-model="selectedStatus" class="radio radio-error">
                                                        <span class="font-medium">Reject
                                                            <p class="text-muted-foreground mt-2">Reject
                                                                This
                                                                Complaint</p>
                                                        </span>
                                                    </div>
                                                </label>

                                                <label
                                                    class="card border cursor-pointer p-4 hover:border-success transition"
                                                    :class="selectedStatus === 'in_progress' ? 'border-success bg-success/5' : 'border-base-300'"
                                                    @click.prevent="selectedStatus = selectedStatus === 'in_progress' ? null : 'in_progress'">
                                                    <div class="flex items-center gap-3">
                                                        <input type="radio" name="status" value="in_progress"
                                                            x-model="selectedStatus" class="radio radio-success">
                                                        <span class="font-medium">
                                                            Approve
                                                            <p class="text-muted-foreground mt-2">This
                                                                complaint
                                                                is acceptable</p>
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>
                                        @elseif ($complaint->status === ComplaintStatus::IN_PROGRESS)
                                            <label
                                                class="card border cursor-pointer p-4 hover:border-primary transition"
                                                :class="selectedStatus === 'pending_confirmation' ? 'border-primary bg-primary/5' : 'border-base-300'"
                                                @click.prevent="selectedStatus = selectedStatus === 'pending_confirmation' ? null : 'pending_confirmation'">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="status" value="pending_confirmation"
                                                        x-model="selectedStatus" class="radio radio-primary">
                                                    <span class="font-medium">Mark as Pending
                                                        Confirmation</span>
                                                </div>
                                            </label>
                                        @elseif ($complaint->status === ComplaintStatus::PENDING_CONFIRMATION)
                                            <label
                                                class="card border cursor-pointer p-4 hover:border-success transition"
                                                :class="selectedStatus === 'resolved' ? 'border-success bg-success/5' : 'border-base-300'"
                                                @click.prevent="selectedStatus = selectedStatus === 'resolved' ? null : 'resolved'">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="status" value="resolved"
                                                        x-model="selectedStatus" class="radio radio-success">
                                                    <span class="font-medium">Mark as Resolved</span>
                                                </div>
                                            </label>
                                        @endif

                                        <x-admin.status-banner />
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

                                {{-- FOOTER: stays visible even when the content above scrolls --}}
                                <div
                                    class="sticky bottom-0 z-10 flex w-full items-center justify-end gap-2 border-t border-base-300 bg-base-100 p-6 pt-4">
                                    <label for="complaint-drawer-{{ $complaint->id }}"
                                        class="btn btn-ghost {{ $isFinal ? ' btn btn-primary w-full' : 'flex-1' }}">
                                        Close
                                    </label>

                                    @unless ($isFinal)
                                        <button type="submit" class="btn btn-primary flex-1">
                                            Update
                                        </button>
                                    @endunless
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="py-12">
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

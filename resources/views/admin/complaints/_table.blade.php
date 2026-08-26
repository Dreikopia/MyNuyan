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
            <th>Date</th>
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
                    <span class="text-xs text-muted-foreground line-clamp-1">
                        {{ $complaint->description }}
                    </span>
                </td>
                <td class="max-w-30">
                    <p class="line-clamp-1 text-xs">
                    <p>{{ $complaint->user->first_name }}</p>
                    <span class="text-xs text-muted-foreground line-clamp-1">
                        {{ $complaint->user->phone_number }}
                    </span>
                    </p>
                </td>

                <td class="whitespace-nowrap text-base-content/70">
                    {{ $complaint->created_at->format('M d') }}
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
                    @if (!$isFinal)
                        <label for="complaint-drawer-{{ $complaint->id }}">
                            <x-icons.view />
                        </label>
                    @else
                        <div class="flex items-center gap-6">
                            <label for="complaint-drawer-{{ $complaint->id }}">
                                <span>
                                    <x-icons.view />
                                </span>
                            </label>

                            @if ($archivedView ?? false)
                                <form method="POST" action="{{ route('admin.complaints.unarchive', $complaint) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <button type="submit">
                                            <x-icons.archive-restore />
                                        </button>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.complaints.archive', $complaint) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit">
                                        <x-icons.archive />
                                    </button>
                                </form>
                            @endif

                        </div>
                    @endif
                    <div class="drawer drawer-end">
                        <input id="complaint-drawer-{{ $complaint->id }}" type="checkbox" class="drawer-toggle" />

                        <div class="drawer-side z-50">
                            <label for="complaint-drawer-{{ $complaint->id }}" aria-label="close sidebar"
                                class="drawer-overlay"></label>

                            <div class="bg-surface min-h-full w-full max-w-xl lg:max-w-2xl flex flex-col">
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
                                                class="textarea textarea-bordered w-full rows-4"
                                                placeholder="Send a remarks" label="Remarks" />
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
                                                Save changes
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

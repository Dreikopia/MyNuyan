@props(['complaints', 'selectedCategory', 'statusCounts', 'categories'])

@php
    use App\Enums\ComplaintStatus;
    use App\Enums\ComplaintPriority;
@endphp

<div class="overflow-x-auto rounded-box border border-base-content/5 bg-card">
    <table class="table table-outline border-2">
        <thead>
            <tr>
                <th>Id</th>
                <th>Category</th>
                <th>Description</th>
                <th>Complainant</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody class="text-xs">
            @forelse ($complaints as $complaint)
                @php
                    $isFinal = in_array(
                        $complaint->status,
                        [ComplaintStatus::RESOLVED, ComplaintStatus::REJECTED],
                        true,
                    );
                @endphp
                <tr>
                    <td>
                        CMP-{{ $complaint->id }}
                    </td>
                    <td>
                        {{ $complaint->category->name }}
                    </td>

                    <td class="max-w-56">
                        <p class="line-clamp-2">
                            {{ $complaint->description }}
                        </p>
                    </td>

                    <td>
                        {{ $complaint->user->first_name }}
                    </td>
                    <td><x-status-badge :status="$complaint->status" /></td>
                    <td>
                        <x-modal id="ReviewComplaint-{{ $complaint->id }}" name="{{ $isFinal ? 'View' : 'Review' }}"
                            class="btn rounded-full {{ $isFinal ? 'btn-ghost' : 'btn-primary/50' }}" boxClass="max-w-3xl">

                            <div class="flex items-center justify-between border-b border-base-300 pb-3 mb-4">
                                <div>
                                    <h3 class="text-lg font-bold">Review</h3>
                                    <p class="text-sm text-base-content/60">
                                        C-{{ str_pad($complaint->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                <x-status-badge :status="$complaint->status" />
                            </div>

                            <x-admin.complaint-details :complaint="$complaint" />

                            <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}"
                                class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <x-admin.complaints-images :complaint="$complaint" />

                                <x-field name="remarks" type="textarea" :value="$complaint->remarks"
                                    class="textarea textarea-bordered w-full rows-4" placeholder="Send a remarks"
                                    label="Remarks" />

                                @unless ($isFinal)
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
                                        <label class="card border cursor-pointer p-4 hover:border-primary transition"
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

                                            <label class="card border cursor-pointer p-4 hover:border-error transition"
                                                :class="selectedStatus === 'rejected' ? 'border-error bg-error/5' : 'border-base-300'"
                                                @click.prevent="selectedStatus = selectedStatus === 'rejected' ? null : 'rejected'">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="status" value="rejected"
                                                        x-model="selectedStatus" class="radio radio-error">
                                                    <span class="font-medium">Reject
                                                        <p class="text-muted-foreground mt-2">Reject This Complaint</p>
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
                                                        <p class="text-muted-foreground mt-2">This complaint is
                                                            acceptable
                                                        </p>
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    @elseif ($complaint->status === ComplaintStatus::IN_PROGRESS)
                                        <label class="card border cursor-pointer p-4 hover:border-primary transition"
                                            :class="selectedStatus === 'pending_confirmation' ? 'border-primary bg-primary/5' : 'border-base-300'"
                                            @click.prevent="selectedStatus = selectedStatus === 'pending_confirmation' ? null : 'pending_confirmation'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="status" value="pending_confirmation"
                                                    x-model="selectedStatus" class="radio radio-primary">
                                                <span class="font-medium">Mark as Pending Confirmation</span>
                                            </div>
                                        </label>
                                    @elseif ($complaint->status === ComplaintStatus::PENDING_CONFIRMATION)
                                        <label class="card border cursor-pointer p-4 hover:border-success transition"
                                            :class="selectedStatus === 'resolved' ? 'border-success bg-success/5' : 'border-base-300'"
                                            @click.prevent="selectedStatus = selectedStatus === 'resolved' ? null : 'resolved'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="status" value="resolved"
                                                    x-model="selectedStatus" class="radio radio-success">
                                                <span class="font-medium">Mark as Resolved</span>
                                            </div>
                                        </label>
                                    @endif

                                    <!-- The dynamic banner -->
                                    <x-admin.status-banner />

                                </div>


                                @if ($complaint->statusHistories->isNotEmpty())
                                    <div class="md:border-r md:border-base-300 md:pr-5 max-h-104 overflow-y-auto">
                                        <h4 class="text-sm font-semibold text-base-content/70 mb-3">
                                            Status History
                                        </h4>

                                        <x-admin.status-timeline :histories="$complaint->statusHistories" />
                                    </div>
                                @endif


                                <div
                                    class="sticky bottom-0 z-50 flex w-full items-center justify-end gap-2 border-t border-base-300 bg-base-100 p-4">
                                    <button type="button" class="btn btn-ghost {{ $isFinal ? 'w-full' : 'flex-1' }}"
                                        onclick="document.getElementById('ReviewComplaint-{{ $complaint->id }}').close()">
                                        Cancel
                                    </button>

                                    @unless ($isFinal)
                                        <button type="submit" class="btn btn-primary flex-1">
                                            Update
                                        </button>
                                    @endunless
                                </div>
                            </form>

                        </x-modal>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-6 text-gray-500">
                        No complaints found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

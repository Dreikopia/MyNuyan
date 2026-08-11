@extends('layouts.admin')

@section('content')

    <div>
        <h1 class="text-2xl font-bold">Complaints</h1>
        <p class="text-sm text-muted-foreground">Manage Complaints</p>
    </div>

    <form method="GET" action="{{ route('admin.complaints') }}" id="filter-form">
        <div class="flex flex-wrap items-center gap-2 mb-4">

            {{-- Status: one long pill --}}
            <div class="join overflow-x-auto">
                <a href="{{ route('admin.complaints', ['category' => $selectedCategory]) }}"
                    class="btn btn-sm join-item text-xs {{ !request('status') ? 'btn-primary' : 'btn-ghost bg-base-200' }}">
                    All
                    <span class="text-xs opacity-70">{{ $statusCounts->get('all') }}</span>
                </a>
                @foreach (App\Enums\ComplaintStatus::cases() as $status)
                    <a href="{{ route('admin.complaints', ['status' => $status->value, 'category' => $selectedCategory]) }}"
                        class="btn btn-sm join-item text-xs {{ request('status') == $status->value ? 'btn-primary' : 'btn-ghost bg-base-200' }}">
                        {{ $status->label() }}
                        <span class="text-xs opacity-70">{{ $statusCounts->get($status->value) }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Search --}}
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search complaints…"
                class="input input-bordered input-sm flex-1 min-w-36 max-w-xs" />

            {{-- Category --}}
            <select name="category" class="select select-bordered select-sm w-30" onchange="this.form.submit()">
                <option value="" {{ !$selectedCategory ? 'selected' : '' }}>All</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (string) $selectedCategory === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-card">

        <table class="table bg-base-300">
            <!-- head -->
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Resident</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-xs">
                @forelse ($complaints as $complaint)
                    <tr>
                        <td>
                            C-{{ $complaint->id }}
                        </td>
                        <td>{{ $complaint->category->name }}</td>
                        <td class="max-w-56">
                            <p class="truncate">
                                {{ $complaint->location }}
                            </p>
                        </td>
                        <td class="max-w-56">
                            <p class="truncate">
                                {{ $complaint->description }}
                            </p>
                        </td>
                        <td>{{ $complaint->user->first_name }}</td>
                        <td><x-status-badge :status="$complaint->status" /></td>
                        <td>
                            <x-modal id="ManageComplaint-{{ $complaint->id }}" name="Manage" class="btn btn-primary">

                                {{-- Header --}}
                                <div class="flex items-center justify-between border-b border-base-300 pb-3 mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold">Manage Complaint</h3>
                                        <p class="text-sm text-base-content/60">
                                            C-{{ str_pad($complaint->id, 3, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                    <x-status-badge :status="$complaint->status" />
                                </div>

                                {{-- Info card --}}
                                <div class="card bg-base-200 p-4 space-y-3 mb-4">
                                    <p class="text-sm leading-relaxed">{{ $complaint->description }}</p>

                                    <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-sm border-t border-base-300 pt-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-base-content/50">Reported by</p>
                                            <p class="font-medium text-error">{{ $complaint->user->first_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-base-content/50">Category</p>
                                            <p class="font-medium">{{ $complaint->category->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-base-content/50">Location</p>
                                            <p class="font-medium">{{ $complaint->location }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-base-content/50">Date Filed</p>
                                            <p class="font-medium">{{ $complaint->created_at->format('M d, Y - h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action form --}}
                                <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}"
                                    class="space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-medium">Update Status</span>
                                        </label>
                                        <select name="status" class="select select-bordered w-full">
                                            <option disabled>Update Status</option>
                                            @foreach (App\Enums\ComplaintStatus::cases() as $status)
                                                <option value="{{ $status->value }}" @selected($complaint->status === $status)>
                                                    {{ ucfirst($status->value) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label class="label">
                                        <span class="label-text font-medium">Remarks</span>
                                    </label>
                                    <x-field name="remarks" type="textarea" :value="$complaint->remarks"
                                        class="textarea textarea-bordered w-full" />

                                    <div class="flex justify-end gap-2 pt-2 border-t border-base-300">
                                        <button type="button" class="btn btn-ghost"
                                            onclick="document.getElementById('ManageComplaint-{{ $complaint->id }}').close()">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>

                            </x-modal>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">
                            No complaints found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

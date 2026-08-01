@extends('layouts.admin')

@section('content')
    <form method="GET" action="{{ route('admin.complaints') }}" id="filter-form">
        <div class="flex flex-wrap items-center gap-2 mb-4">

            {{-- Status: one long pill --}}
            <div class="join overflow-x-auto">
                <a href="{{ route('admin.complaints', ['category' => $selectedCategory]) }}"
                    class="btn btn-sm join-item text-xs {{ !request('status') ? 'btn-primary' : 'btn-ghost bg-base-200' }}">
                    All
                </a>
                @foreach (App\Enum\ComplaintStatus::cases() as $status)
                    <a href="{{ route('admin.complaints', ['status' => $status->value, 'category' => $selectedCategory]) }}"
                        class="btn btn-sm join-item text-xs {{ request('status') == $status->value ? 'btn-primary' : 'btn-ghost bg-base-200' }}">
                        {{ $status->label() }}
                        <span class="text-xs opacity-70">{{ $statusCounts->get($status->value) }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Search --}}
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search complaints…"
                class="input input-bordered input-sm flex-1 min-w-[10rem] max-w-xs" />

            {{-- Category --}}
            <select name="category" class="select select-bordered select-sm w-36" onchange="this.form.submit()">
                <option value="" {{ !$selectedCategory ? 'selected' : '' }}>Category</option>
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
                        <td>{{ $complaint->id }}</td>
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
                        <td><x-admin.status-badge :status="$complaint->status" /></td>
                        <td>
                            <button class="btn btn-sm btn-primary">
                                Manage
                            </button>
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

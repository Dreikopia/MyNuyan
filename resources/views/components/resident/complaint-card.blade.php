@props(['complaint'])

<div class="card w-full bg-base-300 card-md shadow-sm gap-y-5">
    <div class="card-body">
        <x-status-badge :status="$complaint->status" />
        <h2 class="card-title">{{ $complaint->category->name }}</h2>
        <p class="line-clamp-1">{{ $complaint->description }}</p>
        <p>{{ $complaint->location }}</p>
        <p>{{ $complaint->created_at->format('M-d-Y') }}</p>
        <div class="justify-evenly card-actions">
            <button class="btn btn-error btn-outline rounded-3xl">Cancel Report</button>
            <a href="{{ route('complaint.show', $complaint) }}" class="btn btn-primary btn-outline rounded-3xl">
                View Detail
            </a>
        </div>
    </div>
</div>

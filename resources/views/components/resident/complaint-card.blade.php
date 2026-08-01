@props(['complaint'])

<div class="card w-full bg-base-300 card-md shadow-sm gap-y-5">
    <div class="card-body">
        <x-resident.status-badge :status="$complaint->status" />
        <h2 class="card-title">{{ $complaint->category->name }}</h2>
        <p>{{ $complaint->location }}</p>
        <p>{{ $complaint->description }}</p>
        <p>{{ $complaint->created_at->diffForHumans() }}</p>
        <div class="justify-evenly card-actions">
            <button class="btn btn-error btn-outline rounded-3xl">Cancel Report</button>
            <a href="{{ route('complaint.show', $complaint) }}" class="btn btn-primary btn-outline rounded-3xl">
                View Detail
            </a>
        </div>
    </div>
</div>

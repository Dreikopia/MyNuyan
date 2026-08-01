@extends('layouts.admin')

@section('content')
    <div class="card w-50 bg-base-100 card-xs shadow-sm">
        <div class="card-body">
            <h2 class="card-title">All Complaints</h2>
            <p>{{ $complaints->count() }}</p>
        </div>
    </div>
    @
@endsection

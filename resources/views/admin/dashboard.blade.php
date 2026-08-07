@extends('layouts.admin')

@section('content')
    <div class="card w-50 bg-base-100 card-sm shadow-sm rounded-sm">
        <div class="card-body">
            <h2 class="card-title">Complaint 30 days</h2>
            <p>{{ $complaints->count() }}</p>
        </div>
    </div>
@endsection

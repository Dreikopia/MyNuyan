@extends('layouts.admin')

@section('content')
    <x-admin.header title="Dashboard" description="View statistics">
        <button class="btn btn-sm bg-primary">Export</button>
    </x-admin.header>

    <div class="card bg-surface">
        <div class="card-body">

            <h2 class="font-bold text-lg">
                Monthly Complaint Trends
            </h2>

            <p class="text-sm opacity-70">
                Number of complaints submitted over the last 6 months.
            </p>

            <div class="h-60">
                <canvas id="complaintTrendChart" data-chart-data='@json($monthlyComplaints)'></canvas>
            </div>

        </div>
    </div>

    <div class="card card-sm bg-surface">
        <div class="h-80">
            <canvas id="complaintCategoryChart" data-chart-data='@json($categoryComplaints)'></canvas>
        </div>
    </div>
@endsection

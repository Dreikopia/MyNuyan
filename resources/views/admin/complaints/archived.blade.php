@extends('layouts.admin')

@section('content')
    <x-admin.header title="Complaints" :title-url="route('admin.complaints')" :breadcrumbs="[['label' => 'Archived Complaints']]">

    </x-admin.header>
    @include('admin.complaints._table', [
        'complaints' => $complaints,
        'archivedView' => true,
    ])
    {{ $complaints->links() }}
@endsection

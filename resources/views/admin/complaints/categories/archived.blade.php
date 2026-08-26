@extends('layouts.admin')

@section('content')
    <x-admin.header title="Categories" :title-url="route('admin.categories')" :breadcrumbs="[['label' => 'Archived Categories']]" />

    @include('admin.complaints.categories._table', [
        'categories' => $categories,
        'archivedView' => true,
    ])

    {{ $categories->links() }}
@endsection

@extends('layouts.admin')

@section('content')
    <x-admin.header title="News" :title-url="route('admin.news')" :breadcrumbs="[['label' => 'Categories']]">

    </x-admin.header>
@endsection

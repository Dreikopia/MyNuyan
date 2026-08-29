@extends('layouts.admin')

@section('content')
    <x-admin.header title="Complaints" :title-url="route('admin.complaints')" :breadcrumbs="[['label' => 'Categories']]">
        <x-modal id="create-category" name="New Category" class="btn btn-primary">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf

                <div class="flex flex-col space-y-2">
                    <div>
                        <x-field name="category_name" label="Category name" placeholder="Name or type of the category" />
                        <x-field name="description" type="textarea" label="Description"
                            placeholder="What best describe this category" />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">
                            Add
                        </button>
                    </div>
                </div>
            </form>
        </x-modal>
    </x-admin.header>

    <div class="p-4">
        <a href="{{ route('admin.categories.archived') }}" class="btn btn-sm btn-primary/90 text-white">
            <x-icons.archive />
            View Archived
        </a>
    </div>

    <div id="complaint-categories-table">
        @include('admin.complaints.categories._table')
    </div>
@endsection

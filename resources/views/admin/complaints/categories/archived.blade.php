@extends('layouts.admin')

@section('content')
    <x-admin.header title="Categories" :title-url="route('admin.categories')" :breadcrumbs="[['label' => 'Archived Categories']]" />

    <div x-data="{
        search: @js(request('filter.search')),
    
        fetchResults() {
            let params = new URLSearchParams();
    
            if (this.search) {
                params.set('filter[search]', this.search);
            }
    
            fetch('{{ route('admin.categories.archived') }}?' + params.toString(), {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('complaint-categories-table').innerHTML = data.html;
                });
        }
    }">

        <div class="px-10 py-4 flex items-center justify-between">

            <input type="search" x-model="search" x-on:input.debounce.400ms="fetchResults()"
                x-on:keydown.enter.prevent="fetchResults()" autocomplete="off" class="input input-sm bg-transparent w-64"
                placeholder="Search categories...">

            <a href="{{ route('admin.categories') }}" class="btn btn-sm btn-outline">
                <x-icons.back />
                Active Categories
            </a>

        </div>

        <div id="complaint-categories-table">
            @include('admin.complaints.categories._table', [
                'categories' => $categories,
                'archivedView' => true,
            ])
        </div>

    </div>
@endsection

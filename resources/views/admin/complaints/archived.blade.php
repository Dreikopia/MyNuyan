@extends('layouts.admin')

@php
    use App\Enums\ComplaintPriority;
@endphp

@section('content')
    <x-admin.header title="Complaints" :title-url="route('admin.complaints')" :breadcrumbs="[['label' => 'Archived Complaints']]">
    </x-admin.header>

    <div x-data="{
        search: @js(request('filter.search', '')),
        category: @js(request('filter.complaint_category_id', '')),
        priority: @js(request('filter.priority', '')),
    
        loading: false,
    
    
        get hasActiveFilters() {
            return this.category !== '' || this.priority !== '';
        },
    
        clearCategory() {
            this.category = '';
            this.fetchResults();
        },
    
        clearPriority() {
            this.priority = '';
            this.fetchResults();
        },
    
    
        fetchResults() {
            this.loading = true;
    
            const params = new URLSearchParams();
    
            if (this.search) params.set('filter[search]', this.search);
            if (this.category) params.set('filter[complaint_category_id]', this.category);
            if (this.priority) params.set('filter[priority]', this.priority);
    
            fetch('{{ route('admin.complaints.archived') }}?' + params.toString(), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => {
                    return res.json();
                })
                .then(data => {
                    document.getElementById('complaints-table').innerHTML = data.html;
    
                    history.pushState({}, '', '?' + params.toString());
                })
                .catch(error => console.error(error))
                .finally(() => {
                    this.loading = false;
                });
        }
    }">

        <div class="flex items-center gap-3 py-4 flex-wrap">

            <input type="text" x-model="search" x-on:input.debounce.400ms="fetchResults()" autocomplete="off"
                class="input input-sm bg-transparent w-64" placeholder="Search complaints...">

            <select x-model="category" @change="fetchResults()" class="select select-bordered select-sm w-40">
                <option value="">Category: All</option>
                @foreach ($categories as $categoryOption)
                    <option value="{{ $categoryOption->id }}">{{ $categoryOption->name }}</option>
                @endforeach
            </select>

            <select x-model="priority" @change="fetchResults()" class="select select-bordered select-sm w-40">
                <option value="">Priority: All</option>
                @foreach (ComplaintPriority::cases() as $p)
                    <option value="{{ $p->value }}">{{ $p->label() }}</option>
                @endforeach
            </select>

            {{-- Back to active complaints --}}
            <a href="{{ route('admin.complaints') }}" class="btn btn-sm btn-outline">
                <x-icons.back />
                Active Complaints
            </a>

        </div>

        {{-- Table --}}
        <div class="relative">

            {{-- Loading Overlay --}}
            <div x-show="loading" x-transition.opacity
                class="absolute inset-0 bg-base-100/60 flex items-center justify-center z-20 rounded-md">
                <span class="loading loading-spinner loading-sm"></span>
            </div>

            <div class="overflow-x-auto border border-base-content/5 bg-surface rounded-md shadow-sm">
                <div id="complaints-table">
                    @include('admin.complaints._table', [
                        'complaints' => $complaints,
                        'archivedView' => true,
                    ])
                </div>
            </div>

        </div>

    </div>
@endsection

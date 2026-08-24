@extends('layouts.admin')

@php
    use App\Enums\ComplaintStatus;
    use App\Enums\ComplaintPriority;

    $currentLabel = request('filter.status') ? ComplaintStatus::from(request('filter.status'))->label() : 'All';
@endphp

@section('content')
    <x-admin.header title="Complaints" description="Manage and review complaints">
        <button class="btn btn-sm btn-primary">
            Archives
        </button>
    </x-admin.header>

    <div x-data="{
        search: '{{ request('filter.search') }}',
        fetchResults() {
            let params = new URLSearchParams({ 'filter[search]': this.search });
    
            fetch('{{ route('admin.complaints') }}?' + params.toString(), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('complaints-table').innerHTML = data.html;
                });
        }
    }">

        <form method="GET" action="{{ route('admin.complaints') }}" id="filter-form">
            <div class="flex items-center gap-3 pb-2">

                {{-- Search --}}
                <input type="text" x-model="search" x-on:input.debounce.400ms="fetchResults()" autocomplete="off"
                    class="input input-sm bg-transparent w-64" placeholder="Search complaints...">

                {{-- Status --}}
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-sm btn-outline font-normal justify-between w-36">
                        <span>
                            Status: {{ $currentLabel }}
                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-100 w-48 p-2 mt-1 shadow-lg">
                        <li>
                            <a
                                href="{{ route('admin.complaints', [
                                    'filter' => [
                                        'complaint_category_id' => request('filter.complaint_category_id'),
                                        'search' => request('filter.search'),
                                        'priority' => request('filter.priority'),
                                    ],
                                ]) }}">
                                <span>All</span>
                                <span class="opacity-70">
                                    {{ $statusCounts->get('all', 0) }}
                                </span>
                            </a>
                        </li>

                        @foreach (ComplaintStatus::cases() as $status)
                            <li>
                                <a
                                    href="{{ route('admin.complaints', [
                                        'filter' => [
                                            'status' => $status->value,
                                            'complaint_category_id' => request('filter.complaint_category_id'),
                                            'search' => request('filter.search'),
                                            'priority' => request('filter.priority'),
                                        ],
                                    ]) }}">
                                    <span>{{ $status->label() }}</span>
                                    <span class="opacity-70">
                                        {{ $statusCounts->get($status->value, 0) }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <select name="filter[complaint_category_id]" class="select select-bordered select-sm w-40"
                    onchange="this.form.submit()">
                    <option value="">Category:All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="filter[priority]" class="select select-bordered select-sm w-40" onchange="this.form.submit()">
                    <option value="">Priority: All</option>
                    @foreach (ComplaintPriority::cases() as $priority)
                        <option value="{{ $priority->value }}" @selected(request('filter.priority') === $priority->value)>
                            {{ $priority->label() }}
                        </option>
                    @endforeach
                </select>

            </div>
        </form>
        <div class="overflow-x-auto border border-base-content/5 bg-surface rounded-md shadow-sm">
            <div id="complaints-table">
                @include('admin.complaints._table')
            </div>
        </div>
    </div>
@endsection

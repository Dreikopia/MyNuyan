@extends('layouts.admin')

@section('content')
    <x-admin.header title="News" :title-url="route('admin.news')" :breadcrumbs="[['label' => 'Categories']]">
        <x-modal id="create-news-category" name="Create">
            {{-- Your create category form here --}}
        </x-modal>
    </x-admin.header>


    <div class="mt-4">
        <div class="flex items-center justify-between mb-3">

            <div>
                <h2 class="text-sm font-semibold">
                    News Categories
                </h2>
            </div>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto rounded-md border border-base-300">

            <table class="table table-md bg-surface">

                <thead class="text-base-content/70 uppercase text-[11px] tracking-wide bg-gray-700">
                    <tr>
                        <th>Category</th>
                        <th>News</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody class="text-xs divide-y">

                    @forelse ($categories as $category)
                        <tr class="hover:bg-base-200/60 transition-colors">

                            {{-- Category --}}
                            <td>
                                <p class="font-medium">
                                    {{ $category->name }}
                                </p>
                            </td>


                            {{-- News count --}}
                            <td>
                                <span class="badge badge-sm">
                                    {{ $category->news_count }}
                                </span>
                            </td>


                            {{-- Created --}}
                            <td class="whitespace-nowrap text-base-content/70">
                                {{ $category->created_at->diffForHumans() }}
                            </td>


                            {{-- Actions --}}
                            <td>
                                <div class="flex items-center gap-1">

                                    <button type="button" class="btn btn-xs btn-outline">
                                        Edit
                                    </button>

                                    <button type="button" class="btn btn-xs btn-outline btn-error">
                                        Delete
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="py-12">

                                <div class="flex flex-col items-center justify-center text-center gap-1">

                                    <p class="text-base-content/60">
                                        No news categories found.
                                    </p>

                                    <p class="text-sm text-base-content/40">
                                        Create a category to get started.
                                    </p>

                                </div>

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="flex items-center justify-between mt-3">

            <span class="text-[11px] text-base-content/60">

                @if ($categories->total() > 0)
                    {{ $categories->firstItem() }}-{{ $categories->lastItem() }}
                    of {{ $categories->total() }}
                @else
                    0 results
                @endif

            </span>


            <div class="join">

                <a href="{{ $categories->previousPageUrl() ?? '#' }}"
                    class="join-item btn btn-sm btn-outline
                        {{ $categories->onFirstPage() ? 'btn-disabled' : '' }}">
                    Previous
                </a>

                <a href="{{ $categories->nextPageUrl() ?? '#' }}"
                    class="join-item btn btn-sm btn-outline
                        {{ !$categories->hasMorePages() ? 'btn-disabled' : '' }}">
                    Next
                </a>

            </div>

        </div>

    </div>
@endsection

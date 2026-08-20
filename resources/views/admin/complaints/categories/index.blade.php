@extends('layouts.admin')

@section('content')
    <x-admin.header title="New Category" description="Manage news category">
        <x-modal id="CreateCategory" name="New Category" class="btn btn-primary">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="flex flex-col space-y-2">
                    <div>
                        <x-field name="category_name" label="New Category" placeholder="Category Name" />
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

    @if ($categories->isEmpty())
        <div class="flex flex-col items-center justify-center text-center py-16 bg-base-300 rounded-box">
            <p class="text-base-content/60">No categories yet.</p>
            <p class="text-sm text-base-content/40">Click "New Category" to create your first one.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($categories as $category)
                <div class="card bg-surface hover:shadow-md transition-shadow">
                    <div class="card-body">
                        <h2 class="card-title">
                            {{ $category->name }}
                            <span class="text-muted-foreground">{{ $category->complaints_count }}</span>
                        </h2>

                        <div class="card-actions justify-end mt-2 gap-2">
                            <x-modal id="editCategory-{{ $category->id }}" name="Edit"
                                class="btn btn-sm btn-outline btn-primary">
                                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex flex-col gap-4 space-y-2">
                                        <div>
                                            <x-field name="name" label="Edit Category" :value="$category->name" />
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="button" class="btn btn-outline">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </x-modal>

                            <x-modal id="deleteCategory-{{ $category->id }}" name="Delete" boxClass="max-w-sm"
                                class="btn btn-sm btn-error btn-outline">
                                <div class="flex flex-col gap-4">
                                    <div>
                                        <h3 class="font-bold text-lg">Delete category?</h3>
                                        <p class="text-sm text-base-content/70 mt-1">
                                            Are you sure you want to delete
                                            <span class="font-semibold">{{ $category->name }}</span>?
                                            This action cannot be undone.
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.categories.delete', $category) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="flex justify-end gap-2">
                                            <button type="button" class="btn btn-outline">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-error">
                                                Delete
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </x-modal>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

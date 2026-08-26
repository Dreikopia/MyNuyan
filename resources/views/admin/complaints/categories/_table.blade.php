<div class="px-10 overflow-x-auto">
    <table class="table table-md bg-surface">
        <thead class="sticky top-0 z-10 bg-gray-700 text-base-content/70 uppercase text-[11px] tracking-wide">
            <tr>
                <th class="rounded-tl-xl">Id</th>
                <th>Name</th>
                <th>Description</th>
                <th class="text-center">Complaints</th>
                <th class="rounded-tr-xl">Action</th>
            </tr>
        </thead>

        <tbody class="text-xs divide-y">
            @forelse ($categories as $category)
                <tr class="hover:bg-base-200/60 transition-colors">

                    <td class="font-medium">
                        {{ $category->id }}
                    </td>

                    <td class="max-w-50">
                        <p>{{ $category->name }}</p>
                    </td>

                    <td class="max-w-50 line-clamp-">
                        <p class="text-sm text-muted-foreground">
                            {{ $category->description }}
                        </p>
                    </td>

                    <td class="text-center">
                        <p class="font-bold text-muted-foreground text-xl">
                            {{ $category->complaints_count }}
                        </p>
                    </td>
                    <td>
                        <div class="flex gap-5">
                            <button type="button"
                                onclick="document.getElementById('edit-category-{{ $category->id }}').showModal()">
                                <x-icons.edit />
                            </button>

                            <x-modal id="edit-category-{{ $category->id }}" :trigger="false">
                                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                                    @csrf
                                    @method('PATCH')
                                    <x-field name="name" label="Category name" :value="$category->name" />
                                    <x-field name="description" type="textarea" label="Description" :value="$category->description" />
                                    <div class="flex mt-2 justify-end">
                                        <x-button type="submit" class="w-full">
                                            Save Changes
                                        </x-button>
                                    </div>
                                </form>
                            </x-modal>

                            @if ($archivedView ?? false)
                                <form method="POST" action="{{ route('admin.categories.unarchive', $category) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">
                                        <x-icons.archive-restore />
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.categories.archive', $category) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">
                                        <x-icons.archive />

                                    </button>
                                </form>
                            @endif


                            @if ($category->complaints_count)
                                <button type="button"
                                    onclick="document.getElementById('delete-category-{{ $category->id }}').showModal()">
                                    <x-icons.trash />
                                </button>
                            @endif

                            <x-modal id="delete-category-{{ $category->id }}" :trigger="false">
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                    @csrf
                                    @method('DELETE')
                                    Delete {{ $category->name }} category?
                                    <div class="flex justify-end mt-2">
                                        <x-button type="submit" class="btn btn-outline">
                                            Cancel
                                        </x-button>
                                        <x-button type="submit" class="btn btn-error">
                                            Confirm Deletion
                                        </x-button>
                                    </div>
                                </form>
                            </x-modal>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-12">
                        <div class="flex flex-col items-center justify-center text-center gap-1">
                            <p class="text-base-content/60">
                                No categories found.
                            </p>

                            <p class="text-sm text-base-content/40">
                                Try adjusting your filters or search.
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

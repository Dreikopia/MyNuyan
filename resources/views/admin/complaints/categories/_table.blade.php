<table class="table table-md bg-surface w-full">

    <thead class="sticky top-0 z-10 bg-gray-700 text-base-content/70 uppercase text-[11px] tracking-wide">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Description</th>
            <th class="text-center">categorys</th>
            <th class="text-center">Default Priority</th>
            <th>Action</th>
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

                <td class="max-w-50">
                    <p class="text-sm text-muted-foreground line-clamp-1">
                        {{ $category->description }}
                    </p>
                </td>

                <td class="text-center">
                    <p class="font-bold text-muted-foreground text-xl">
                        {{ $category->categorys_count }}
                    </p>
                </td>


                <td>
                    @php
                        $priorityClasses = match ($category->default_priority->value) {
                            'low' => 'bg-green-500/10 text-green-500 border-green-500/20',
                            'medium' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                            'high' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                            'urgent' => 'bg-red-500/10 text-red-500 border-red-500/20',
                        };
                    @endphp
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @csrf
                        @method('PATCH')

                        <div class="relative inline-block">
                            <select name="default_priority" onchange="this.form.submit()"
                                class="appearance-none cursor-pointer inline-flex items-center rounded-full border pl-2.5 pr-5 py-1 text-xs font-medium {{ $priorityClasses }}">
                                @foreach (App\Enums\ComplaintPriority::cases() as $priority)
                                    <option value="{{ $priority->value }}" @selected($category->default_priority->value === $priority->value)>
                                        {{ $priority->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-1.5 top-1/2 h-2.5 w-2.5 -translate-y-1/2"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                            </svg>
                        </div>

                    </form>
                </td>

                <td>
                    <div class="flex gap-5">

                        {{-- Edit --}}
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

                                <div class="form-control mt-2">
                                    <label class="label">
                                        <span class="label-text">Default Priority</span>
                                    </label>

                                </div>
                                <div class="flex mt-2 justify-end">
                                    <x-button type="submit" class="w-full">
                                        Save Changes
                                    </x-button>
                                </div>

                            </form>
                        </x-modal>

                        {{-- Archive / Restore --}}
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

                        {{-- Delete --}}
                        @if ($category->categorys_count)
                            <button type="button"
                                onclick="document.getElementById('delete-category-{{ $category->id }}').showModal()">
                                <x-icons.trash />
                            </button>
                        @endif

                        <x-modal id="delete-category-{{ $category->id }}" :trigger="false">
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                @csrf
                                @method('DELETE')

                                <p>
                                    Delete {{ $category->name }} category?
                                </p>

                                <div class="flex justify-end mt-2 gap-2">

                                    <x-button type="button" class="btn btn-outline"
                                        onclick="document.getElementById('delete-category-{{ $category->id }}').close()">
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
                <td colspan="6" class="py-12">

                    <div class="flex flex-col items-center justify-center text-center gap-1">

                        <p class="text-base-content/60">
                            No categories found.
                        </p>

                        <p class="text-sm text-base-content/40">
                            Try adjusting your search.
                        </p>

                    </div>

                </td>
            </tr>
        @endforelse

    </tbody>

</table>

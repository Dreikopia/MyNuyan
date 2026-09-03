@extends('layouts.admin')

@section('content')
    <x-admin.header title="Hotlines">
        <x-modal id="create-hotline" name="New hotline" class="btn btn-primary" boxClass="bg-surface">
            <form method="POST" action="{{ route('admin.hotlines.store') }}">
                @csrf
                <div class="flex flex-col space-y-2">
                    <div>
                        <x-field name="name" label="Hotline name" placeholder="Name or type of the hotline" />
                        <x-field name="phone_number" label="Hotline number" placeholder="What best describe this hotline" />

                        <x-field name="hotline_category_id" type="select" label="Category">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-field>
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

    @php
        // Maps a category name to a color theme (info/error/success/warning/primary).
        // Add more names here as your categories grow.
        $colorFor = fn($name) => match (strtolower($name)) {
            'police' => 'info',
            'fire' => 'error',
            'medical', 'ambulance', 'health' => 'success',
            'disaster', 'rescue' => 'warning',
            default => 'primary',
        };
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
        @forelse ($hotlines as $hotline)
            @php
                $color = $colorFor($hotline->category->name);
                $isActive = ($hotline->status ?? 'active') === 'active';
            @endphp

            <div
                class="card bg-surface border border-base-300 hover:border-{{ $color }}/50 hover:shadow-lg transition-all duration-200 overflow-hidden group">

                {{-- Top accent bar in the category color --}}
                <div class="h-1 bg-{{ $color }}"></div>

                <div class="p-4 flex flex-col gap-3">

                    {{-- Category label --}}
                    <div class="flex items-center justify-between">
                        <span
                            class="badge badge-sm bg-{{ $color }}/10 text-{{ $color }} border-none font-medium uppercase tracking-wide text-[10px]">
                            {{ $hotline->category->name }}
                        </span>

                        {{-- Actions dropdown --}}
                        <div class="dropdown dropdown-end opacity-0 group-hover:opacity-100 transition-opacity">
                            <label tabindex="0" class="btn btn-ghost btn-xs btn-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1.5" />
                                    <circle cx="12" cy="12" r="1.5" />
                                    <circle cx="12" cy="19" r="1.5" />
                                </svg>
                            </label>
                            <ul tabindex="0"
                                class="dropdown-content menu menu-sm z-20 p-2 shadow-lg bg-base-100 border border-base-300 rounded-box w-32">
                                <li>
                                    <label for="edit-hotline-{{ $hotline->id }}" class="cursor-pointer">
                                        Edit
                                    </label>
                                </li>
                                <li>
                                    <form method="POST" action="" onsubmit="return confirm('Delete this hotline?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-error w-full text-left">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Icon + name --}}
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center size-11 rounded-full bg-{{ $color }}/10 text-{{ $color }} shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a2.25 2.25 0 0 0-2.25.549l-.66.66a12.75 12.75 0 0 1-6.632-6.632l.66-.66a2.25 2.25 0 0 0 .549-2.25L6.916 4.024A1.125 1.125 0 0 0 5.825 3.25H4.5A2.25 2.25 0 0 0 2.25 5.5v1.25Z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-sm truncate">{{ $hotline->name }}</p>
                            <p class="text-xs text-base-content/50">
                                {{ $isActive ? 'Active' : 'Inactive' }}
                            </p>
                        </div>
                    </div>

                    {{-- Status badge + phone number --}}
                    <div class="flex items-center justify-between pt-2 border-t border-base-300">
                        <span class="badge badge-xs {{ $isActive ? 'badge-success' : 'badge-ghost' }} gap-1">
                            <span
                                class="size-1.5 rounded-full {{ $isActive ? 'bg-success' : 'bg-base-content/30' }}"></span>
                            {{ $isActive ? 'Active' : 'Inactive' }}
                        </span>
                        <a href="tel:{{ $hotline->phone_number }}"
                            class="font-mono text-sm font-bold text-{{ $color }} hover:underline">
                            {{ $hotline->phone_number }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Edit modal --}}
            <x-modal id="edit-hotline-{{ $hotline->id }}" name="" class="hidden" boxClass="bg-surface">
                <h3 class="font-bold text-lg mb-4">Edit hotline</h3>
                <form method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-col space-y-2">
                        <x-field name="name" label="Hotline name" :value="$hotline->name" />
                        <x-field name="phone_number" type="number" label="Hotline number" :value="$hotline->phone_number" />
                        <x-field name="hotline_category_id" type="select" label="Category">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($hotline->hotline_category_id === $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-field>
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </x-modal>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center text-center gap-1 py-16">
                <p class="text-base-content/60">No hotlines yet.</p>
                <p class="text-sm text-base-content/40">Click "New hotline" to add your first one.</p>
            </div>
        @endforelse
    </div>
@endsection

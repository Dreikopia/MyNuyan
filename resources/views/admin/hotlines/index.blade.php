@extends('layouts.admin')

@section('content')
    <x-admin.header title="Hotlines">
        <x-modal id="create-hotline" name="New hotline" class="btn btn-primary" boxClass="bg-surface">
            <form method="POST" action="{{ route('admin.hotlines.store') }}">
                @csrf
                <div class="flex flex-col space-y-2">
                    <x-field name="name" label="Hotline name" placeholder="e.g. Barangay Health Center" />
                    <x-field name="location" label="Location" placeholder="e.g. Purok 3, near the plaza" />
                    <x-field name="contact_number" label="Contact number" placeholder="09xxxxxxxxx" />

                    <x-field name="hotline_category_id" type="select" label="Category">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-field>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </x-modal>
    </x-admin.header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
        @forelse ($hotlines as $hotline)
            @php
                $isActive = ($hotline->status ?? 'active') === 'active';
                $numbers = $hotline->numbers;
                $primaryNumber = $numbers->firstWhere('is_primary', true) ?? $numbers->first();
                $visibleNumbers = $numbers->take(2);
                $extraCount = max($numbers->count() - 2, 0);
            @endphp

            <div
                class="card bg-surface border border-base-300 hover:shadow-lg transition-all duration-200 h-full flex flex-col">
                <div class="p-4 flex flex-col gap-3 h-full">

                    {{-- Top section: neutral icon + status badge --}}
                    <div class="flex items-start justify-between">
                        <div
                            class="flex items-center justify-center size-11 rounded-full bg-base-200 text-base-content/60 shrink-0">
                            {{-- Neutral default icon — category has no icon field yet --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a2.25 2.25 0 0 0-2.25.549l-.66.66a12.75 12.75 0 0 1-6.632-6.632l.66-.66a2.25 2.25 0 0 0 .549-2.25L6.916 4.024A1.125 1.125 0 0 0 5.825 3.25H4.5A2.25 2.25 0 0 0 2.25 5.5v1.25Z" />
                            </svg>
                        </div>

                        <span class="badge {{ $isActive ? 'badge-success' : 'badge-ghost' }} gap-1">
                            <span
                                class="size-1.5 rounded-full {{ $isActive ? 'bg-success' : 'bg-base-content/40' }}"></span>
                            {{ $isActive ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    {{-- Hotline info --}}
                    <div>
                        <p class="font-semibold text-sm">{{ $hotline->name }}</p>
                        <p class="text-xs text-base-content/50">{{ $hotline->category?->name ?? 'Uncategorized' }}</p>
                        <div class="flex items-start gap-1.5 mt-1 text-xs text-base-content/60">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 shrink-0 mt-0.5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <span class="line-clamp-2">
                                {{ $hotline->location ?? 'No location set' }}
                            </span>
                        </div>
                    </div>

                    {{-- Contact numbers --}}
                    <div class="border-t border-base-300 pt-2" x-data="{ open: false }">
                        @if ($numbers->isEmpty())
                            <p class="text-xs text-base-content/40 italic">No contact numbers added yet.</p>
                        @else
                            <ul class="space-y-1">
                                @foreach ($visibleNumbers as $number)
                                    <li class="flex items-center justify-between text-sm">
                                        <a href="tel:{{ $number->number }}" class="font-mono hover:underline">
                                            {{ $number->number }}
                                        </a>
                                        <span class="flex items-center gap-1">
                                            @if ($number->is_primary)
                                                <span class="badge badge-xs badge-primary">Primary</span>
                                            @endif
                                            @if ($number->type)
                                                <span
                                                    class="badge badge-xs badge-ghost">{{ ucfirst($number->type) }}</span>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>

                            @if ($extraCount > 0)
                                <button type="button" @click="open = !open" :aria-expanded="open . toString()"
                                    aria-label="Show {{ $extraCount }} more contact numbers"
                                    class="text-xs text-primary mt-1 hover:underline">
                                    <span x-show="!open">+ {{ $extraCount }} more</span>
                                    <span x-show="open" x-cloak>Show less</span>
                                </button>

                                <ul class="space-y-1 mt-1" x-show="open" x-cloak x-collapse>
                                    @foreach ($numbers->slice(2) as $number)
                                        <li class="flex items-center justify-between text-sm">
                                            <a href="tel:{{ $number->number }}" class="font-mono hover:underline">
                                                {{ $number->number }}
                                            </a>
                                            <span class="flex items-center gap-1">
                                                @if ($number->is_primary)
                                                    <span class="badge badge-xs badge-primary">Primary</span>
                                                @endif
                                                @if ($number->type)
                                                    <span
                                                        class="badge badge-xs badge-ghost">{{ ucfirst($number->type) }}</span>
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    </div>

                    {{-- Bottom actions --}}
                    <div class="flex gap-2 mt-auto pt-2">
                        <a href="" class="btn btn-sm btn-outline flex-1">
                            View
                        </a>
                        <label for="edit-hotline-{{ $hotline->id }}" class="btn btn-sm btn-primary flex-1 cursor-pointer">
                            Edit
                        </label>
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
                        <x-field name="location" label="Location" :value="$hotline->location" />
                        <x-field name="hotline_category_id" type="select" label="Category">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($hotline->hotline_category_id === $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-field>

                        {{-- Numbers are managed separately since a hotline can have many --}}
                        <p class="text-xs text-base-content/50 pt-1">
                            To add or edit contact numbers, use the View page.
                        </p>

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

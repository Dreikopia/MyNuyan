@extends('layouts.resident')

@section('content')
    @if (session('error'))
        <div class="alert alert-error mt-4">{{ session('error') }}</div>
    @endif


    <div class="mt-2">
        <a href="{{ route('home') }}">
            <x-icons.back />
        </a>
    </div>

    <h1 class="text-xl font-bold mt-4 mb-4">
        Select a category that best describe the situation
    </h1>

    {{-- pb-24 (or however tall your fixed bar is) so content isn't hidden behind it --}}
    <form id="category-form" action="{{ route('complaints.create.category.store') }}" method="POST" class="pb-24">
        @csrf

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($categories as $category)
                <label
                    class="card bg-base-100 shadow p-4 cursor-pointer has-checked:ring-2 has-checked:ring-primary has-checked:bg-primary/5">
                    <input type="radio" name="complaint_category_id" value="{{ $category->id }}"
                        class="radio radio-primary mb-2 sr-only" required
                        {{ old('complaint_category_id') == $category->id ? 'checked' : '' }}>
                    <span class="font-semibold">{{ $category->name }}</span>
                </label>
            @endforeach
        </div>

        @error('complaint_category_id')
            <p class="text-error text-sm mt-2">{{ $message }}</p>
        @enderror
    </form>

    {{-- Fixed bottom action bar --}}
    <div
        class="fixed bottom-0 left-0 right-0 bg-base-100 border-t border-base-300 p-4 pb-[calc(1rem+env(safe-area-inset-bottom))]">
        <button type="submit" form="category-form" class="btn btn-primary w-full rounded-full">
            Continue
        </button>
    </div>
@endsection

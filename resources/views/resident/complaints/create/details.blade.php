@extends('layouts.resident')


@section('content')
    {{-- <x-resident.breadcrumbs :items="$breadcrumbs" /> --}}
    @if (session('error'))
        <div class="alert alert-error mt-4">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error mt-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex items-center gap-15">
        <x-icons.back />
        <h1 class="text-xl font-bold mt-4 mb-4">{{ $category->name }}</h1>
    </div>

    <form action="{{ route('complaints.create.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 group">
        @csrf

        <div>
            <x-field name="location" label="location" placeholder="e.g. Purok 3, near the barangay hall" />
        </div>

        <div>
            <label class="label">Attached your photos</label>
            <input type="file" name="images[]" multiple accept="image/*" class="file-input file-input-bordered w-full">
            @error('images.*')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="label">Description <span class="text-error">*</span></label>
            <textarea name="description" class="textarea textarea-bordered w-full" rows="4"
                placeholder="Describe the issue in detail..." required>{{ old('description') }}</textarea>
        </div>



        <div
            class="fixed bottom-0 left-0 right-0 bg-base-100 border-t border-base-300 p-4 pb-[calc(1rem+env(safe-area-inset-bottom))]">
            <button type="submit"
                class="btn btn-primary flex-1 group-has-invalid:btn-disabled group-has-invalid:opacity-50 w-full rounded-full">
                Submit Complaint
            </button>
        </div>
    </form>

@endsection

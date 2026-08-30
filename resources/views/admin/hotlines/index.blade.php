@extends('layouts.admin')

@section('content')
    <x-admin.header title="Hotlines">
        <x-modal id="create-hotline" name="New hotline" class="btn btn-primary">
            <form method="POST" action="{{ route('admin.hotlines.store') }}">
                @csrf
                <div class="flex flex-col space-y-2">
                    <div>
                        <x-field name="name" label="Hotline name" placeholder="Name or type of the hotline" />
                        <x-field name="phone_number" type="number" label="Hotline number"
                            placeholder="What best describe this hotline" />

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

    @foreach ($hotlines as $hotline)
        <div class="card">
            <div class="card-body">
                {{ $hotline->phone_number }}
            </div>
        </div>
    @endforeach
@endsection

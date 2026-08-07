@extends('layouts.resident')

@section('content')
    <div class="flex min-h-screen h-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-full">
                Log out
            </button>
        </form>
    </div>
    <x-resident.bottom-nav />
@endsection

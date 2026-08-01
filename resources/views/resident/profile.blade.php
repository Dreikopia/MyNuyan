@extends('layouts.resident')

@section('content')
    Profile Page
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-primary w-full">
            Log out
        </button>
    </form>
    <x-resident.bottom-nav />
@endsection

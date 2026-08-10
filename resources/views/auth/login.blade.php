@extends('layouts.resident.guest')

@section('content')
    <div class="pt-12 space-y-3">
        <h2 class="text-2xl font-bold">
            My<span class="text-primary">Nuyan</span>
        </h2>

        <p class="font-bold">
            Sign in
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div class="join w-full">
            <span class="join-item flex items-center border border-base-300 bg-base-200 px-3 text-base-content">
                +63
            </span>
            <input type="text" name="phone_number" class="input join-item flex-1" placeholder="9123456789">
        </div>
        <x-error name="phone_number" />

        <x-field name="password" label="Password" type="password" />

        <button type="submit" class="btn btn-primary w-full rounded-full">
            Sign in
        </button>

        <a href="{{ route('home') }}" class="block text-center font-semibold text-primary">
            Continue as Guest
        </a>
    </form>
@endsection

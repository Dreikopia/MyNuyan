@extends('layouts.resident.guest')

@section('content')
    <div class="pt-12 space-y-3">
        <h2 class="text-2xl font-bold">
            My<span class="text-primary">Nuyan</span>
        </h2>

        <p class="font-bold">
            Enter your phone number to instantly set up your new account.
        </p>
    </div>

    <form method="POST" action="/register" class="space-y-5">
        @csrf
        <div class="join w-full">
            <span class="join-item flex items-center border border-base-300 bg-base-200 px-3 text-base-content">
                +63
            </span>
            <input type="text" name="phone_number" class="input join-item flex-1" placeholder="9123456789">
        </div>
        <x-resident.error name="phone_number" />
        <x-resident.field name="first_name" label="First name" />
        <x-resident.field name="last_name" label="Last name" />
        <x-resident.field name="username" label="Username" placeholder="Don't user your real name" />
        <x-resident.field name="password" label="Password" type="password" />

        <button type="submit" class="btn btn-primary w-full rounded-full">
            Create account
        </button>

        <a href="{{ route('home') }}" class="block text-center font-semibold text-primary">
            Continue as Guest
        </a>

        <a href="{{ route('login') }}" class="block text-center font-semibold text-primary">
            Log in
        </a>
    </form>
@endsection

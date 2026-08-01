@extends('layouts.resident')

<x-resident.header />

@section('content')
    <div class="flex justify-between p-4">
        <h2 class="text-balance pt-15 font-bold text-xl">Good day to you,<br>
            @auth
                {{ ucfirst(Auth::user()->first_name) }}
            @endauth
            @guest
                <a href="{{ route('register') }}" class="btn btn-outlined w-fit rounded-full">
                    Create an account
                </a>
            @endguest
        </h2>
        <div>
            <x-icons.notification />
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-secondary w-full card-lg rounded-xl">
            <div class="card-body">
                <h2 class="card-title text-sm font-bold">Help us improve our barangay</h2>
                <p class="text-sm">Spotted an issue? Contact us so we can solve it</p>
                <div class="flex justify-center gap-x-2">
                    @auth
                        <a href="{{ route('complaint.index') }}" class="btn btn-sm btn-primary btn-outline rounded-full">
                            View my reports
                        </a>
                        <a href="{{ route('complaints.create.category') }}" class="btn btn-sm btn-primary rounded-full">
                            Submit a report
                        </a>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary rounded-full w-full">
                            Sign in
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        <div>
            <div class="space-y-1 mb-2">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold">Discover Latest news</h2>
                    <a href="" class="text-primary text-sm font-bold">See more</a>
                </div>
                <p class="text-sm">Latest news and updates for Minuyan Proper</p>
            </div>
            <div class="-mx-4">
                <img src="images/medical-mission.webp" class="w-full h-50 object-cover">
            </div>
            <h2 class="text-primary text-lg font-bold">Community</h2>
            <h2 class="text-xl font-bold">Free checkup ni kalbo</h2>
        </div>
        <x-resident.bottom-nav />
    @endsection

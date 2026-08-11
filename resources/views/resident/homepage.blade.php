@extends('layouts.resident')

<x-resident.header />

@section('content')
    <div class="space-y-2">
        <div class="flex justify-between items-center p-4">
            <div>
                <p class="font-koho text-muted-foreground">
                    Good day to you,
                </p>
                @auth
                    <h2 class="font-koho text-xl font-bold">
                        {{ ucfirst(Auth::user()->first_name) }}
                    </h2>
                @endauth

                @guest
                    <a href="{{ route('register') }}" class="btn btn-outlined w-fit rounded-full">
                        Create an account
                    </a>
                @endguest
            </div>

            <div>
                <x-icons.notification />
            </div>
        </div>

        @auth
            <a href="{{ route('complaint.index') }}" class="block">
                <div class="bg-secondary w-full card-lg rounded-xl flex items-center gap-4 p-4">
                    <div class="shrink-0">
                        <x-icons.track />
                    </div>
                    <div>
                        <h2 class="card-title text-md font-koho font-bold text-primary">My Complaints</h2>
                        <p class="text-md font-koho text-muted-foreground">View and track all your complaints</p>
                    </div>
                </div>
            </a>
            <div class="grid grid-cols-4 gap-2">
                <div class="card bg-surface card-md rounded-xl">
                    <div class="card-body flex flex-col justify-end items-center text-center h-full">
                        <span class="text-2xl font-bold text-primary">24</span>
                        <span class="text-sm text-muted-foreground">Total</span>
                    </div>
                </div>
                <div class="card bg-surface card-md rounded-xl">
                    <div class="card-body flex flex-col justify-end items-center text-center h-full">
                        <span class="text-2xl font-bold text-primary">15</span>
                        <span class="text-sm text-muted-foreground">Resolved</span>
                    </div>
                </div>
                <div class="card bg-surface card-md rounded-xl">
                    <div class="card-body flex flex-col justify-end items-center text-center h-full">
                        <span class="text-2xl font-bold text-primary">6</span>
                        <span class="text-sm text-muted-foreground">Active</span>
                    </div>
                </div>
                <div class="card bg-surface card-md rounded-xl">
                    <div class="card-body flex flex-col justify-end items-center text-center h-full">
                        <span class="text-2xl font-bold text-primary">3</span>
                        <span class="text-xs text-muted-foreground">Resolved</span>
                    </div>
                </div>
            </div>
        @endauth

        <div class="bg-secondary w-full card-lg rounded-xl flex flex-col items-stretch gap-4 px-4 py-6">
            <div class="flex-1 flex items-center">
                <h2 class="card-title text-sm font-koho font-bold">Help us to make our barangay to be a much safer,
                    cleaner, and better place
                </h2>
            </div>
            <div class="w-full">
                @auth
                    <a href="{{ route('complaints.create.category') }}" class="btn btn-sm btn-primary rounded-sm w-full">
                        Submit a report
                    </a>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary rounded-sm w-full">
                        Sign in
                    </a>
                @endguest
            </div>
        </div>

        <div class="flex justify-between text-sm font-bold">
            <h2>Discover latest news</h2>
            <p class="text-primary">See more</p>
        </div>

        <div class="card bg-surface w-full shadow-sm">
            <figure>
                <img src="images/medical-mission.webp" alt="kalbo" class="max-h-30 w-full" />
            </figure>
            <div class="card-body">
                <h2 class="card-title text-primary">Health</h2>
                <p class="text-md">Free checkup ni O-Black</p>
            </div>
        </div>
    </div>




    <x-resident.bottom-nav />
@endsection

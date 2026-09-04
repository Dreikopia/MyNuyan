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



        <div class="bg-secondary w-full card-lg rounded-xl flex flex-col items-stretch gap-4 px-4 py-6">
            <div class="flex-1 flex items-center">
                <h2 class="card-title text-sm font-koho font-bold">Help us to make our barangay to be a much safer,
                    cleaner, and better place
                </h2>
            </div>
            <div class="w-full">
                @auth
                    <a href="{{ route('complaints.create.category') }}" class="btn btn-sm bg-primary/50 rounded-sm w-full">
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

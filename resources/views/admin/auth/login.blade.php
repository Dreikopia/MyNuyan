@extends('layouts.admin.guest')

@section('content')
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="bg-surface card-border min-h-screen w-1/2 flex items-center">
            <div class="card-body">
                <h2 class="card-title">Welcome</h2>
                <x-field name="username" label="Username" />
                <x-field name="password" type="password" label="Password" />
                <button class="btn btn-primary w-full">Log in</button>
            </div>
        </div>
    @endsection

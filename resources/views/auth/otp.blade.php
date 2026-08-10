@extends('layouts.resident')

@section('content')
    <div class="flex gap-x-5 items-center">
        <a href="{{ route('register') }}">
            <x-icons.back />
        </a>
    </div>
    <div class="pt-12 space-y-3 pb-4">
        <h2 class="text-2xl font-bold">
            Verify Your Phones </h2>

        <p class="font-bold">
            We sent 6 digits authentication code
        </p>
    </div>

    @if (session('status'))
        <p style="color:green">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <label class="otp">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <input type="text" name="code" autocomplete="one-time-code" inputmode="numeric" maxlength="6"
                pattern="[0-9]{6}" required />
        </label>

        <button type="submit" class="btn btn-primary">Verify</button>
    </form>

    {{-- <form method="POST" action="{{ route('otp.resend') }}">
    @csrf
    <button type="submit">Resend code</button>
</form> --}}
@endsection

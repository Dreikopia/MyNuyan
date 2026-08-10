<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request, OtpService $otpService)
    {
        $validated = $request->validate([
            'phone_number' => ['max_digits:11', 'numeric'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'password' => ['required', Password::default()],
        ]);

        // store the details to the session first
        session(['pending_registration' => [
            'phone_number' => $validated['phone_number'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'password' => $validated['password'],
        ]]);

        $this->sendOtpTo($validated['phone_number'], $otpService);

        return redirect()->route('otp-form');
    }

    public function showOtpForm()
    {
        if (! session('pending_registration')) {
            return redirect()->route('register');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request, OtpService $otpService)
    {

        $request->validate(['code' => 'required|digits:6']);

        $pending = session('pending_registration');

        if (! $otpService->verify($pending['phone_number'], $request->code)) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        // OTP correct — NOW we actually create the User
        $user = User::create([
            ...$pending,
            'phone_verified_at' => now(),
        ]);

        Auth::login($user);

        session()->forget('pending_registration');

        return redirect()->route('home')->with('success', 'Phone verified! Welcome.');
    }

    // public function resendOtp()
    // {
    //     $pending = session('pending_registration');
    //     $this->sendOtpTo($pending['phone_number']);

    //     return back()->with('status', 'A new code was sent.');
    // }

    private function sendOtpTo(string $phone, OtpService $otpService)
    {
        $key = 'otp-request:'.$phone;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            abort(429, "Please wait {$seconds}s before requesting another code.");
        }

        RateLimiter::hit($key, 60);

        $code = app(OtpService::class)->generate($phone);

        // swap this line later for real SMS — everything else stays the same
        Log::info("OTP for {$phone}: {$code}");
    }
}

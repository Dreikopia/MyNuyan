<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'phone_number' => ['max_digits:11', 'numeric'],
            'password' => ['required'],
        ]);

        $key = 'login-attempt:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors([
                'phone_number' => "Too many attempts. Try again in {$seconds}s.",
            ]);
        }
        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'phone_number' => 'Incorrect phone number or password.',
        ])->onlyInput('phone_number');
    }

    /**
     * Log the user out.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

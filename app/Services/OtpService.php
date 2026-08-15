<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function generate(string $phone): string
    {
        $code = (string) random_int(100000, 999999);

        Cache::put("otp:{$phone}", $code, now()->addMinute(5));

        return $code;
    }

    public function verify(string $phone, string $inputCode): bool
    {
        $storedCode = Cache::get("otp:{$phone}");

        if ($storedCode && hash_equals($storedCode, $inputCode)) {
            Cache::forget("otp:{$phone}");

            return true;
        }

        return false;
    }

    public function send(string $phone, string $code): void
    {
        // swap this for real SMS
        Log::info("OTP for {$phone}: {$code}");
    }
}

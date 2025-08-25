<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function signin(string $email, string $password, string $remember, string $ip): void
    {
        $key = Str::lower('signin|'.$email.'|'.$ip);
        $cumulativeKey = Str::lower('signin_cumulative|'.$email.'|'.$ip);

        $cumulativeAttempts = Cache::get($cumulativeKey, 0);

        $lockoutDurations = [
            5 => 300,      // 5 mins
            10 => 600,     // 10 mins
            15 => 3600,    // 1 hour
            20 => 86400,   // 24 hours
        ];

        $lockout = 60; // default 1 min
        foreach ($lockoutDurations as $threshold => $duration) {
            if ($cumulativeAttempts > $threshold) {
                $lockout = $duration;
            }
        }

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            if ($lockout >= 86400) {
                $message = 'Too many attempts. Try again in 24 hours.';
            } elseif ($lockout >= 3600) {
                $message = 'Too many attempts. Try again in 1 hour.';
            } elseif ($lockout >= 600) {
                $message = 'Too many attempts. Try again in 10 minutes.';
            } elseif ($lockout >= 300) {
                $message = 'Too many attempts. Try again in 5 minutes.';
            } else {
                $message = "Too many attempts. Try again in {$seconds} seconds.";
            }

            throw ValidationException::withMessages([
                'email' => $message,
            ]);
        }

        $credentials = ['email' => $email, 'password' => $password];

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($key, $lockout);

            Cache::put($cumulativeKey, $cumulativeAttempts + 1, now()->addDay());

            throw ValidationException::withMessages([
                'email' => 'Invalid email or password',
            ]);
        }

        RateLimiter::clear($key);
        Cache::forget($cumulativeKey);

        if ($remember) {
            Auth::setRememberDuration(43200);
        }

        $user = Auth::user();
        $role = $user?->roles->first();

        $isValid =
            $user &&
            $user->active &&
            $role &&
            $role->active &&
            optional($role->pivot)->active &&
            in_array($role->name, [
                Role::ROLE_SUPERADMIN,
                Role::ROLE_ADMIN,
                Role::ROLE_HEADMASTER,
            ]);

        if (! $isValid) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Invalid email or password',
            ]);
        }
    }

    public function signout(): void
    {
        Auth::logout();
    }
}

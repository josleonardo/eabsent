<?php

namespace App\Http\Controllers;

use App\Http\Requests\SigninRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signinIndex()
    {
        return view('signin', ['pageName' => 'Sign in']);
    }

    /**
     * Handle sign in authentication attempt.
     */
    public function signin(SigninRequest $request): RedirectResponse
    {
        $email = $request->input('email');
        $key = Str::lower('login|' . $email . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many attempts. Try again in {$seconds} seconds.",
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');
        
        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => 'Invalid email or password',
            ]);
        }

        RateLimiter::clear($key);

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
                Role::ROLE_HEADMASTER
            ]);

        if (! $isValid) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Invalid email or password',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('home.index');
    }

    /**
     * Sign the user out of the application.
     */
    public function signout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('signin.index');
    }
}

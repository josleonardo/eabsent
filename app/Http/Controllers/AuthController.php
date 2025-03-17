<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function signin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->has('remember');
        if ($remember) {
            Auth::setRememberDuration(43200);
        }

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            $allowedRoles = [1, 2, 3];

            if (!$user || !$user->active || !$user->roleActive($allowedRoles)) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'credentials' => 'Invalid credentials'
                ]);
            }

            $request->session()->regenerate();
            return redirect()->route('home.index');
        }

        throw ValidationException::withMessages([
            'credentials' => 'Invalid credentials'
        ]);
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

<?php

namespace App\Http\Controllers;

use App\Models\Role;
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
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ]);

        $remember = $request->filled('remember');
        if ($remember) {
            Auth::setRememberDuration(43200);
        }

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'credentials' => 'Invalid credentials',
            ]);
        }

        $user = Auth::user();
        $role = $user?->roles->first();

        $isValid =
            $user &&
            $user->active &&
            $role &&
            $role->active &&
            optional($role->pivot)->active &&
            in_array($role->name, [Role::ROLE_SUPERADMIN, Role::ROLE_ADMIN, Role::ROLE_HEADMASTER]);

        if (! $isValid) {
            Auth::logout();
            throw ValidationException::withMessages([
                'credentials' => 'Invalid credentials',
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

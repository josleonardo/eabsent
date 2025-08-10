<?php

namespace App\Http\Controllers;

use App\Http\Requests\SigninRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function signin(SigninRequest $request, AuthService $authService): RedirectResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->filled('remember');
        $ip = $request->ip();

        try {
            $authService->signin($email, $password, $remember, $ip);
        } catch (ValidationException $e) {
            return back()->withInput($request->only('email'))->withErrors($e->errors());
        }

        $request->session()->regenerate();

        return redirect()->route('home.index');
    }

    /**
     * Sign the user out of the application.
     */
    public function signout(Request $request, AuthService $authService): RedirectResponse
    {
        $authService->signout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('signin.index');
    }
}

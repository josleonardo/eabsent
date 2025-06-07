<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display the user's account settings page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->roles->first();
        $hasChangePassword = $role->menus->where('id', 15)->first();

        return view('settings.account', ['pageName' => 'Account Settings'] + compact('user', 'hasChangePassword'));
    }

    /**
     * Update the current user's email.
     */
    public function updateEmail(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|max:255|unique:users,email,'.$request->user()->id,
        ]);

        $user = $request->user();
        $validatedData['updated_by'] = $user->id;

        $user->update($validatedData);

        return back()->with('success', 'Your email updated successfully');
    }

    /**
     * Update the current user's username.
     */
    public function updateUsername(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.$request->user()->id,
        ]);

        $user = $request->user();
        $validatedData['updated_by'] = $user->id;

        $user->update($validatedData);

        return back()->with('success', 'Your username updated successfully');
    }

    /**
     * Update the current user's password.
     */
    public function updatePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required|string|min:8|max:255',
            'new_password' => [
                'required',
                'string',
                'min:8', // Minimum 8 characters
                'max:255',
                'confirmed', // Must match the confirmation field
                'regex:/[0-9]/', // Must contain a number
                'regex:/[!-\/:-@[-`{-~]/', // Must contain a special character
            ],
        ]);

        $user = $request->user();
        $role = $user->roles->first();
        $hasChangePassword = $role->menus->where('id', 15)->first();
        if (! $hasChangePassword) {
            abort(403, 'You do not have permission to change the password.');
        }

        // Verify current password
        if (! Hash::check($validatedData['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Hash the new password
        $user->password = Hash::make($validatedData['new_password']);
        $user->updated_by = $user->id;
        $user->save();

        return back()->with('success', 'Your password updated successfully');
    }
}

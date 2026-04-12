<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateEmailRequest;
use App\Http\Requests\Settings\UpdateLanguageRequest;
use App\Http\Requests\Settings\UpdatePasswordRequest;
use App\Http\Requests\Settings\UpdateUsernameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        $languages = config('constants.languages');

        return view('settings.account', ['pageName' => 'Account Settings'] + compact('user', 'hasChangePassword', 'languages'));
    }

    /**
     * Update the current user's email.
     */
    public function updateEmail(UpdateEmailRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $user = $request->user();
            $validatedData['updated_by'] = $user->id;

            $user->update($validatedData);

            return back()->with('success', 'Your email updated successfully');
        } catch (\Throwable $th) {
            Log::error($th);

            return back()->with('error', 'An error occurred while updating your email. Please try again later.');
        }
    }

    /**
     * Update the current user's username.
     */
    public function updateUsername(UpdateUsernameRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $user = $request->user();
            $validatedData['updated_by'] = $user->id;

            $user->update($validatedData);

            return back()->with('success', 'Your username updated successfully');
        } catch (\Throwable $th) {
            Log::error($th);

            return back()->with('error', 'An error occurred while updating your username. Please try again later.');
        }
    }

    /**
     * Update the current user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validatedData = $request->validated();

        try {
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
        } catch (\Throwable $th) {
            Log::error($th);

            return back()->with('error', 'An error occurred while updating your password. Please try again later.');
        }
    }

    /**
     * Update the current user's language.
     */
    public function updateLanguage(UpdateLanguageRequest $request) {
        $validatedData = $request->validated();

        try {
            $user = $request->user();
            $validatedData['updated_by'] = $user->id;

            $user->update($validatedData);

            return back()->with('success', 'Your language updated successfully');
        } catch (\Throwable $th) {
            Log::error($th);

            return back()->with('error', 'An error occurred while updating your language. Please try again later.');
        }
    }
}

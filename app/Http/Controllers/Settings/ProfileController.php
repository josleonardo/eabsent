<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateProfileRequest;
use App\Services\AvatarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile settings page.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return view('settings.profile', ['pageName' => 'Your Profile'] + compact('user'));
    }

    /**
     * Update the current user's profile.
     */
    public function update(UpdateProfileRequest $request, AvatarService $avatarService)
    {
        $validatedData = $request->validated();

        try {
            $user = $request->user();
            $currentUserId = $user->id;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $path = $avatarService->upload($file, $currentUserId, $user->profile->avatar);
                $validatedData['avatar'] = $path;
            }

            $validatedData['updated_by'] = $currentUserId;
            $user->profile()->update($validatedData);

            return back()->with('success', 'Your profile updated successfully');
        } catch (\Throwable $th) {
            Log::error($th);

            return back()->with('error', 'An error occurred while updating your profile.');
        }
    }
}

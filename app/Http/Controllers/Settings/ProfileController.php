<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\AvatarService;
use Illuminate\Http\Request;

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
        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $avatarService->upload($file, $user->id, $user->profile->avatar);
            $validatedData['avatar'] = $path;
        }

        $validatedData['updated_by'] = $user->id;
        $user->profile()->update($validatedData);

        return back()->with('success', 'Your profile updated successfully');
    }
}

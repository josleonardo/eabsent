<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AvatarService
{
    public function upload(UploadedFile $file, int $userId, ?string $oldAvatar = null): string
    {
        // Delete old avatar if it exists
        if ($oldAvatar && Storage::disk('public')->exists($oldAvatar)) {
            Storage::disk('public')->delete($oldAvatar);
        }

        $avatarName = 'avatar_'.time().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs("users/{$userId}/avatars", $avatarName, 'public');

        return $path;
    }
}

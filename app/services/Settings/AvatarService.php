<?php

namespace App\Services\Settings;

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

        $avatarName = 'avatar-'.time().'-'.mt_rand(0, 1000000000).'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs("users/{$userId}/avatars", $avatarName, 'public');

        return $path;
    }
}

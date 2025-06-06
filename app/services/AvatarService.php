<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class AvatarService
{
    public function upload(UploadedFile $file, int $userId): string
    {
        $avatarName = 'avatar_'.time().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs("users/{$userId}/avatars", $avatarName, 'public');

        return $path;
    }
}

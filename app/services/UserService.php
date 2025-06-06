<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }

    /**
     * Create a new user with the provided validated data and default data.
     */
    public function createUser(array $validatedData, array $defaultData): User
    {
        return DB::transaction(function () use ($validatedData, $defaultData) {
            // Create user credentials
            $user = User::create(array_merge([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
            ], $defaultData));

            // Create user profile
            $user->profile()->create(array_merge([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'nik' => $validatedData['nik'],
                'nuptk' => $validatedData['nuptk'],
                'position' => $validatedData['position'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
                'employment_start' => $validatedData['employment_start'],
                'employment_end' => $validatedData['employment_end'],
            ], $defaultData));

            // If avatar uploaded, store the avatar
            if (isset($validatedData['avatar']) && $validatedData['avatar']->isValid()) {
                $path = $this->avatarService->upload($validatedData['avatar'], $user->id);

                $user->profile()->update(['avatar' => $path]);
            }

            // Attach the role to the user
            if (! empty($validatedData['role'])) {
                $user->roles()->attach($validatedData['role'], $defaultData);
            }

            // Attach the level to the user
            if (! empty($validatedData['level'])) {
                $user->levels()->attach($validatedData['level'], $defaultData);
            }

            // Attach the schedules to the user
            $scheduleIds = [];
            if (! empty($validatedData['schedule'])) {
                $scheduleIds = explode(',', $validatedData['schedule']);

                // Validate all IDs exist in DB
                $validIds = Schedule::whereIn('id', $scheduleIds)->pluck('id')->toArray();
                if (count($validIds) !== count($scheduleIds)) {
                    throw new \Exception('One or more selected schedules are invalid.');
                }

                $user->schedules()->attach($scheduleIds, $defaultData);
            }

            return $user;
        });
    }

    /**
     * Update an existing user with the provided validated data and default data.
     */
    public function updateUser(User $user, array $validatedData, array $defaultData, array $defaultSync): User
    {
        return DB::transaction(function () use ($user, $validatedData, $defaultData, $defaultSync) {
            // If avatar uploaded, update the avatar
            if (isset($validatedData['avatar']) && $validatedData['avatar']->isValid()) {
                // Delete old avatar if it exists
                if ($user->profile->avatar && Storage::disk('public')->exists($user->profile->avatar)) {
                    Storage::disk('public')->delete($user->profile->avatar);
                }

                $path = $this->avatarService->upload($validatedData['avatar'], $user->id);

                $validatedData['avatar'] = $path;
            }

            // Update user credentials
            $user->update(array_merge([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
            ], $defaultData));

            // Update user profile
            $user->profile()->update(array_merge([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'nik' => $validatedData['nik'],
                'nuptk' => $validatedData['nuptk'],
                'position' => $validatedData['position'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
                'employment_start' => $validatedData['employment_start'],
                'employment_end' => $validatedData['employment_end'],
                'avatar' => $validatedData['avatar'] ?? $user->profile->avatar,
            ], $defaultData));

            // Sync the role to the user
            if (! empty($validatedData['role'])) {
                $user->roles()->syncWithPivotValues([$validatedData['role']], $defaultSync);
            } else {
                $user->roles()->detach();
            }

            // Sync the level to the user
            if (! empty($validatedData['level'])) {
                $user->levels()->syncWithPivotValues([$validatedData['level']], $defaultSync);
            } else {
                $user->levels()->detach();
            }

            // Sync the schedules to the user
            $scheduleIds = [];
            if (! empty($validatedData['schedule'])) {
                $scheduleIds = explode(',', $validatedData['schedule']);

                // Validate all IDs exist in DB
                $validIds = Schedule::whereIn('id', $scheduleIds)->pluck('id')->toArray();
                if (count($validIds) !== count($scheduleIds)) {
                    return back()->withErrors(['schedule' => 'One or more selected schedules are invalid.']);
                }

                $user->schedules()->syncWithPivotValues($scheduleIds, $defaultSync);
            } else {
                $user->schedules()->detach();
            }

            return $user;
        });
    }
}

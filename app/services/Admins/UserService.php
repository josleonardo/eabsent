<?php

namespace App\Services\Admins;

use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use App\Services\Settings\AvatarService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private function userQuery(User $user)
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $userRole = $user->roles->first()->name ?? null;

        if ($userRole == $superAdmin) {
            return User::query();
        }

        if ($userRole == $admin) {
            return User::whereHas('roles', function ($query) use ($superAdmin) {
                $query->whereNot('name', $superAdmin);
            })->with('roles:id,name');
        }

        abort(403, 'Unauthorized');
    }

    public function getUsers(User $user, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? config('constants.default_per_page');

        return $this->userQuery($user)->paginate($perPage);
    }

    /**
     * Create a new user with the provided validated data and default data.
     */
    public function createUser(array $validatedData, int $currentUserId): User
    {
        $defaultData = [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ];

        return DB::transaction(function () use ($validatedData, $defaultData) {
            $user = User::create(array_merge([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
                'school_location_id' => $validatedData['school_location_id'],
                'schedule_group_id' => $validatedData['schedule_group_id'],
            ], $defaultData));

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
                $avatarService = app(AvatarService::class);
                $path = $avatarService->upload($validatedData['avatar'], $user->id);

                $user->profile()->update(['avatar' => $path]);
            }

            if (! empty($validatedData['role'])) {
                $user->roles()->attach($validatedData['role'], $defaultData);
            }

            if (! empty($validatedData['level'])) {
                $user->levels()->attach($validatedData['level'], $defaultData);
            }

            return $user;
        });
    }

    /**
     * Update an existing user with the provided validated data and default data.
     */
    public function updateUser(User $user, array $validatedData, int $currentUserId): User
    {
        $defaultData = [
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ];

        $defaultSync = [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ];

        return DB::transaction(function () use ($user, $validatedData, $defaultData, $defaultSync) {
            // If avatar uploaded, update the avatar
            if (isset($validatedData['avatar']) && $validatedData['avatar']->isValid()) {
                $avatarService = app(AvatarService::class);
                $path = $avatarService->upload($validatedData['avatar'], $user->id, $user->profile->avatar);

                $validatedData['avatar'] = $path;
            }

            $user->update(array_merge([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'school_location_id' => $validatedData['school_location_id'],
                'schedule_group_id' => $validatedData['schedule_group_id'],
            ], $defaultData));

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

            if (! empty($validatedData['role'])) {
                $user->roles()->syncWithPivotValues([$validatedData['role']], $defaultSync);
            } else {
                $user->roles()->detach();
            }

            if (! empty($validatedData['level'])) {
                $user->levels()->syncWithPivotValues([$validatedData['level']], $defaultSync);
            } else {
                $user->levels()->detach();
            }

            return $user;
        });
    }

    public function exportUsers(User $user)
    {
        return $this->userQuery($user)->get();
    }
}

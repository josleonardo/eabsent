<?php

namespace App\Services\Admins;

use App\Models\AppSetting;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AppSettingService
{
    public function getAppSettings(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userRole == $superAdmin || $userRole == $admin) {
            return AppSetting::paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function createAppSetting(array $validatedData, int $currentUserId): AppSetting
    {
        $appSetting = AppSetting::create([
            'name' => $validatedData['setting_name'],
            'key' => $validatedData['key'],
            'value_1' => $validatedData['value_1'],
            'value_2' => $validatedData['value_2'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return $appSetting;
    }

    public function updateAppSetting(AppSetting $appSetting, array $validatedData, int $currentUserId): AppSetting
    {
        $appSetting->update([
            'name' => $validatedData['setting_name'],
            'key' => $validatedData['key'],
            'value_1' => $validatedData['value_1'],
            'value_2' => $validatedData['value_2'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return $appSetting;
    }
}

<?php

namespace App\Services\Admins;

use App\Models\Role;
use App\Models\Schedule;
use App\Models\ScheduleGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ScheduleGroupService
{
    public function getScheduleGroups(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userRole == $superAdmin || $userRole == $admin) {
            return ScheduleGroup::with(
                'schedules:id,day_of_week,check_in_time,check_out_time,active'
            )
                ->paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function getSchedulesForSelection(): Collection
    {
        return Schedule::select('id', 'day_of_week', 'check_in_time', 'check_out_time')
            ->where('active', true)
            ->orderBy('day_of_week')
            ->orderBy('check_in_time')
            ->get()
            ->groupBy('day_of_week');
    }

    public function getScheduleGroupForEdit(int $id): ScheduleGroup
    {
        return ScheduleGroup::with(
            'schedules:id,day_of_week,check_in_time,check_out_time,active'
        )
            ->findOrFail($id);
    }

    public function createScheduleGroup(array $validatedData, int $currentUserId): ScheduleGroup
    {
        return DB::transaction(function () use ($validatedData, $currentUserId) {

            $group = ScheduleGroup::create([
                'name' => $validatedData['group_name'],
                'active' => $validatedData['active'],
                'created_by' => $currentUserId,
                'updated_by' => $currentUserId,
            ]);

            if (!empty($validatedData['schedule_ids'])) {
                $pivotData = collect($validatedData['schedule_ids'])
                    ->mapWithKeys(fn($id) => [
                        $id => [
                            'active' => $validatedData['active'],
                            'created_by' => $currentUserId,
                            'updated_by' => $currentUserId,
                        ],
                    ])
                    ->toArray();

                $group->schedules()->sync($pivotData);
            }

            return $group;
        });
    }

    public function updateScheduleGroup(ScheduleGroup $scheduleGroup, array $validatedData, int $currentUserId): ScheduleGroup
    {
        return DB::transaction(function () use ($scheduleGroup, $validatedData, $currentUserId) {

            $scheduleGroup->update([
                'name' => $validatedData['group_name'],
                'active' => $validatedData['active'],
                'updated_at' => now(),
                'updated_by' => $currentUserId,
            ]);

            if (!empty($validatedData['schedule_ids'])) {
                $result = $scheduleGroup->schedules()->sync($validatedData['schedule_ids'] ?? []);
                
                foreach ($validatedData['schedule_ids'] ?? [] as $scheduleId) {
                    $scheduleGroup->schedules()->updateExistingPivot($scheduleId, [
                        'active' => $validatedData['active'],
                        'updated_by' => $currentUserId,
                        'updated_at' => now(),
                    ]);
                }

                foreach ($result['attached'] as $scheduleId) {
                    $scheduleGroup->schedules()->updateExistingPivot($scheduleId, [
                        'created_by' => $currentUserId,
                    ]);
                }
            } else {
                $scheduleGroup->schedules()->detach();
            }

            return $scheduleGroup;
        });
    }
}

<?php

namespace App\Traits;

use App\Services\Utilities\ActivityLogService;

trait ActivityLogTrait
{
    public static function bootActivityLogTrait()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $activityLogService = app(ActivityLogService::class);
                $activityLogService->log($event, $model);
            });
        }
    }
}

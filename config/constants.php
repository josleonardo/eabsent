<?php
return [
    // Active Status
    'active' => [
        0 => 'global.active.inactive',
        1 => 'global.active.active',
    ],

    // Platforms
    'platforms' => [
        0 => 'global.platform.web',
        1 => 'global.platform.mobile',
    ],

    // Attendance Status
    'attendance_status' => [
        0 => ['label' => 'global.attendance.absent', 'color' => 'bg-red-200 hover:bg-red-300 dark:bg-red-900 dark:hover:bg-red-800'],
        1 => ['label' => 'global.attendance.present', 'color' => null],
        2 => ['label' => 'global.attendance.late', 'color' => 'bg-orange-200 hover:bg-orange-300 dark:bg-orange-900 dark:hover:bg-orange-800'],
        3 => ['label' => 'global.attendance.undertime', 'color' => null],
        4 => ['label' => 'global.attendance.on_leave', 'color' => 'bg-blue-200 hover:bg-blue-300 dark:bg-blue-900 dark:hover:bg-blue-800'],
        5 => ['label' => 'global.attendance.holiday', 'color' => 'bg-white hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-800'],
        6 => ['label' => 'global.attendance.rest_day', 'color' => null],
        7 => ['label' => 'global.attendance.half_day', 'color' => null],
        8 => ['label' => 'global.attendance.overtime', 'color' => null],
        9 => ['label' => 'global.attendance.no_schedule', 'color' => 'bg-white hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-800'],
    ],

    // Approve Status
    'approve_status' => [
        0 => 'global.approve.rejected',
        1 => 'global.approve.approved',
    ],

    // Day Names
    'days' => [
        0 => 'global.day.sunday',
        1 => 'global.day.monday',
        2 => 'global.day.tuesday',
        3 => 'global.day.wednesday',
        4 => 'global.day.thursday',
        5 => 'global.day.friday',
        6 => 'global.day.saturday',
    ],

    // Yes/No Options
    'yes_no' => [
        0 => 'global.option.no',
        1 => 'global.option.yes',
    ],
];

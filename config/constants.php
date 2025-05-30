<?php

return [
    // Active Status
    'actives' => [
        0 => ['active' => 'global.active.inactive', 'color' => 'bg-red-300 hover:bg-red-400 dark:bg-red-900 dark:hover:bg-red-800'],
        1 => ['active' => 'global.active.active', 'color' => null],
    ],

    // Platforms
    'platforms' => [
        0 => 'global.platform.web',
        1 => 'global.platform.mobile',
    ],

    // Attendance Status
    'attendance_status' => [
        0 => ['status' => 'global.attendance.absent', 'color' => 'bg-red-200 hover:bg-red-300 dark:bg-red-900 dark:hover:bg-red-800'],
        1 => ['status' => 'global.attendance.present', 'color' => null],
        2 => ['status' => 'global.attendance.late', 'color' => 'bg-orange-200 hover:bg-orange-300 dark:bg-orange-900 dark:hover:bg-orange-800'],
        3 => ['status' => 'global.attendance.undertime', 'color' => null],
        4 => ['status' => 'global.attendance.on_leave', 'color' => 'bg-blue-200 hover:bg-blue-300 dark:bg-blue-900 dark:hover:bg-blue-800'],
        5 => ['status' => 'global.attendance.holiday', 'color' => 'bg-white hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-800'],
        6 => ['status' => 'global.attendance.rest_day', 'color' => null],
        7 => ['status' => 'global.attendance.half_day', 'color' => null],
        8 => ['status' => 'global.attendance.overtime', 'color' => null],
        9 => ['status' => 'global.attendance.no_schedule', 'color' => 'bg-white hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-800'],
    ],

    // Approve Status
    'approve_status' => [
        0 => ['status' => 'global.approve.rejected', 'color' => 'bg-red-300 hover:bg-red-400 dark:bg-red-900 dark:hover:bg-red-800'],
        1 => ['status' => 'global.approve.approved', 'color' => null],
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

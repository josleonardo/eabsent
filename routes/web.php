<?php

use App\Http\Controllers\Admins\AdminController;
use App\Http\Controllers\Admins\AppSettingController;
use App\Http\Controllers\Admins\LevelController;
use App\Http\Controllers\Admins\MenuController;
use App\Http\Controllers\Admins\RoleController;
use App\Http\Controllers\Admins\RoleMenuController;
use App\Http\Controllers\Admins\ScheduleController;
use App\Http\Controllers\Admins\UserController;
use App\Http\Controllers\Admins\UserLevelController;
use App\Http\Controllers\Admins\UserRoleController;
use App\Http\Controllers\Admins\UserScheduleController;
use App\Http\Controllers\Approvals\ApprovalController;
use App\Http\Controllers\Approvals\CorrectionController;
use App\Http\Controllers\Approvals\LeaveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Reports\AttendanceController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Settings\AccountController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/', 'signinIndex')->name('signin.index');
    Route::post('/', 'signin')->name('signin');
});

Route::post('/signout', [AuthController::class, 'signout'])->name('signout');

Route::middleware('auth')->group(function () {
    Route::get('/settings/profile', [ProfileController::class, 'index'])->name('settings.profile');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');

    Route::get('/settings/account', [AccountController::class, 'index'])->name('settings.account');
    Route::put('/settings/account/email', [AccountController::class, 'updateEmail'])->name('settings.account.email.update');
    Route::put('/settings/account/username', [AccountController::class, 'updateUsername'])->name('settings.account.username.update');

    Route::middleware('menu.access.check')->group(function () {
        // Dashboard
        Route::get('/home', function () {
            return view('home', ['pageName' => 'Home']);
        })->name('home.index');

        // Settings change password
        Route::put('/settings/account/password', [AccountController::class, 'updatePassword'])->name('change-password.update');

        // Approval pages
        Route::get('/approval', [ApprovalController::class, 'index'])
            ->name('approval.index');
        Route::resource('/approval/leave', LeaveController::class)
            ->only(['index', 'update']);
        Route::resource('/approval/correction', CorrectionController::class)
            ->only(['index', 'update']);

        // Report pages
        Route::get('/report', [ReportController::class, 'index'])
            ->name('report.index');
        Route::resource('/report/attendance', AttendanceController::class)
            ->only(['index', 'edit', 'update']);

        // Admin pages
        Route::get('/admin', [AdminController::class, 'index'])
            ->name('administration.index');

        Route::resource('/admin/user', UserController::class)
            ->except(['destroy']);
        Route::resource('/admin/role', RoleController::class)
            ->except(['show', 'destroy']);
        Route::resource('/admin/level', LevelController::class)
            ->except(['show', 'destroy']);
        Route::resource('/admin/menu', MenuController::class)
            ->except(['show', 'destroy']);
        Route::resource('/admin/schedule', ScheduleController::class)
            ->except(['show', 'destroy']);
        Route::resource('/admin/app-setting', AppSettingController::class)
            ->except(['show', 'destroy']);

        Route::resource('/admin/user-role', UserRoleController::class)
            ->only(['index', 'edit', 'update']);
        Route::resource('/admin/user-level', UserLevelController::class)
            ->only(['index', 'edit', 'update']);

        Route::get('/admin/user-schedule', [UserScheduleController::class, 'index'])
            ->name('user-schedule.index');
        Route::post('/admin/user-schedule', [UserScheduleController::class, 'store'])
            ->name('user-schedule.store');
        Route::get('/admin/user-schedule/create', [UserScheduleController::class, 'create'])
            ->name('user-schedule.create');
        Route::get('/admin/user-schedule/{user}/{schedule}/edit', [UserScheduleController::class, 'edit'])
            ->name('user-schedule.edit');
        Route::put('/admin/user-schedule/{user}/{schedule}', [UserScheduleController::class, 'update'])
            ->name('user-schedule.update');

        Route::get('/admin/role-menu', [RoleMenuController::class, 'index'])
            ->name('role-menu.index');
        Route::post('/admin/role-menu', [RoleMenuController::class, 'store'])
            ->name('role-menu.store');
        Route::get('/admin/role-menu/create', [RoleMenuController::class, 'create'])
            ->name('role-menu.create');
        Route::get('/admin/role-menu/{role}/{menu}/edit', [RoleMenuController::class, 'edit'])
            ->name('role-menu.edit');
        Route::put('/admin/role-menu/{role}/{menu}', [RoleMenuController::class, 'update'])
            ->name('role-menu.update');
    });
});

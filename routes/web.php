<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CorrectionController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLevelController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/', 'signinIndex')->name('signin.index');
    Route::post('/', 'signin')->name('signin');
});

Route::post('/signout', [AuthController::class, 'signout'])->name('signout');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home', ['pageName' => 'Home']);
    })->name('home.index');

    Route::middleware('role_check:1,2,3')->group(function () {
        // Approval pages
        Route::get('/approval', function () {
            return view('approvals.approvals', ['pageName' => 'Approval']);
        })->name('approvals.index');

        Route::resource('/leave', LeaveController::class)
            ->parameters(['leaves' => 'leave'])
            ->only(['index', 'update']);
        Route::resource('/correction', CorrectionController::class)
            ->only(['index', 'update']);

        // Report pages
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        Route::resource('/report/attendance', AttendanceController::class)
            ->only(['index', 'edit', 'update']);
    });

    Route::middleware('role_check:1,2')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

        Route::resource('/admin/user', UserController::class)
            ->except(['destroy']);
        Route::resource('/admin/schedule', ScheduleController::class)
            ->except(['show', 'destroy']);
        Route::resource('/admin/user-schedule', UserScheduleController::class)
            ->only(['index', 'edit', 'update']);
    });

    Route::middleware('role_check:1')->group(function () {
        Route::resource('/admin/menu', MenuController::class)
            ->except(['show', 'destroy']);
        Route::resource('/admin/role', RoleController::class)
            ->except(['show', 'destroy']);
        Route::resource('/admin/level', LevelController::class)
            ->except(['show', 'destroy']);
        Route::resource('/admin/app-setting', SettingController::class)
            ->except(['show', 'destroy']);
        Route::resource('/admin/user-role', UserRoleController::class)
            ->only(['index', 'edit', 'update']);
        Route::resource('/admin/user-level', UserLevelController::class)
            ->only(['index', 'edit', 'update']);
    });
});

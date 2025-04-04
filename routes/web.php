<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CorrectionController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
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
        Route::get('/approvals', function () {
            return view('approvals.approvals', ['pageName' => 'Approvals']);
        })->name('approvals.index');

        Route::resource('leaves', LeaveController::class)
            ->parameters(['leaves' => 'leave'])
            ->only(['index', 'update']);
        Route::resource('corrections', CorrectionController::class)
            ->only(['index', 'update']);

        // Report pages
        Route::get('/reports', function () {
            return view('reports.reports', ['pageName' => 'Reports']);
        })->name('reports.index');
        Route::resource('attendances', AttendanceController::class)
            ->only(['index', 'edit', 'update']);
    });

    Route::middleware('role_check:1,2')->group(function () {
        Route::get('/admin', function () {
            return view('administrators.admin', ['pageName' => 'Admin']);
        })->name('admin.index');

        Route::resource('admin/user', UserController::class)
            ->except(['destroy']);
        Route::resource('admin/schedule', ScheduleController::class)
            ->except(['show', 'destroy']);
    });

    Route::middleware('role_check:1')->group(function () {
        Route::resource('admin/menu', MenuController::class)
            ->except(['show', 'destroy']);
        Route::resource('admin/role', RoleController::class)
            ->except(['show', 'destroy']);
        Route::resource('admin/level', LevelController::class)
            ->except(['show', 'destroy']);
        Route::resource('admin/setting', SettingController::class)
            ->except(['show', 'destroy']);
    });
});

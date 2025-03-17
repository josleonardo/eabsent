<?php

use App\Http\Controllers\AuthController;
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

    Route::middleware('role_check:1,2')->group(function () {
        Route::resource('admin/user', UserController::class);
        Route::resource('admin/schedule', ScheduleController::class);
    });

    Route::middleware('role_check:1')->group(function () {
        Route::resource('admin/menu', MenuController::class);
        Route::resource('admin/role', RoleController::class);
        Route::resource('admin/level', LevelController::class);
        Route::resource('admin/setting', SettingController::class);
    });
});
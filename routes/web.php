<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('signin', ['pageName' => 'Sign In']);
})->name('signin.index');
Route::get('/home', function () {
    return view('home', ['pageName' => 'Home']);
})->name('home.index');
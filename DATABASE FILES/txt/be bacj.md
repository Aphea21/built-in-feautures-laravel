<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
*/
Route::get('/welcome', function () {
    return view('user.welcome');
});
Route::get('/login', function () {
    return view('layout.login');

}


/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Staff Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');
});

/*
|--------------------------------------------------------------------------
| Driver Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:driver'])->prefix('drivers')->group(function () {
    Route::get('/dashboard', function () {
        return view('drivers.dashboard');
    })->name('drivers.dashboard');
});

/*
|--------------------------------------------------------------------------
| User / Customer Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});

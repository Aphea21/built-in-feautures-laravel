<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControllers;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [UserControllers::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserControllers::class, 'register'])->name('register');
Route::get('/login', [UserControllers::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserControllers::class, 'login'])->name('login');
Route::get('/verify-otp', [UserControllers::class, 'showVerifyForm'])->name('verify-otp');
Route::post('/verify-otp', [UserControllers::class, 'verifyOtp'])->name('verify-otp');
Route::post('/resend-otp', [UserControllers::class, 'resendOtp'])->name('resend-otp');
Route::get('/home',[UserControllers::class,'home'])->name('home');
Route::post('/logout',[UserControllers::class,'logout'])->name('logout');
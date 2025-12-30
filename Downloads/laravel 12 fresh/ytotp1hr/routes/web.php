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


// Forgot Password - Step 1: Request OTP
Route::get('/forgot-password', [UserControllers::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password', [UserControllers::class, 'sendForgotPasswordOtp'])->name('forgot-password.submit');

// Forgot Password - Step 2: Verify OTP
Route::get('/forgot-password/verify-otp', [UserControllers::class, 'showForgotPasswordOtpForm'])->name('forgot-password-otp-verify');
Route::post('/forgot-password/verify-otp', [UserControllers::class, 'verifyForgotPasswordOtp'])->name('verify-forgot-password-otp');

// Forgot Password - Step 3: Reset Password
Route::get('/reset-password', [UserControllers::class, 'showResetPasswordForm'])->name('reset-password.form');
Route::post('/reset-password', [UserControllers::class, 'resetPassword'])->name('reset-password.submit');

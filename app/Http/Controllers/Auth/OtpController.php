<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\OtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function show()
    {
        return view('auth.otp-verify');
    }


public function verify(Request $request)
{
    $user = Auth::user();

    // Rate limit: max 5 attempts per 10 minutes per user
    $key = 'otp-verification:' . $user->id;

    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);
        return back()->withErrors([
            'otp' => "Too many attempts. Please try again in {$seconds} seconds."
        ]);
    }

    $request->validate([
        'otp' => ['required', 'digits:6'],
    ]);

    if (!$user || !$user->otp || !$user->otp_expires_at || (string)$user->otp !== trim($request->otp) || now()->greaterThan($user->otp_expires_at)) {
        RateLimiter::hit($key, 600); // 10 minutes
        return back()->withErrors(['otp' => 'Invalid or expired OTP']);
    }

    // OTP correct → clear attempts
    RateLimiter::clear($key);

    $user->update([
        'otp' => null,
        'otp_expires_at' => null,
        'email_verified_at' => now(),
    ]);

    return redirect()->route('dashboard')->with('success', 'OTP verified! Welcome!');
}


public function resend()
{
    $user = Auth::user();

    // Rate limit: max 3 resends per 10 minutes
    $key = 'otp-resend:' . $user->id;
    if (RateLimiter::tooManyAttempts($key, 3)) {
        $seconds = RateLimiter::availableIn($key);
        return back()->withErrors(['otp' => "You can resend OTP in {$seconds} seconds."]);
    }

    $otp = (string) rand(100000, 999999);
    $user->update([
        'otp' => $otp,
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    $user->notify(new \App\Notifications\OtpNotification($otp));

    RateLimiter::hit($key, 600); // 10 minutes

    return back()->with('success', 'OTP resent successfully!');
}

}

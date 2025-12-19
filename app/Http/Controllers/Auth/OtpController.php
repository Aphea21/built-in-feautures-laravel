<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function show()
    {
        return view('auth.otp-verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $user = Auth::user();

        if ($user && $user->otp === $request->otp && now()->lt($user->otp_expires_at)) {
            $user->otp =  null;
            $user->otp_expires_at = null;
            $user->save();
            return redirect()->route('dashboard')->with('success', 'OTP verified!');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP']);
    }
}

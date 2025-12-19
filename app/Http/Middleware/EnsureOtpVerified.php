<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOtpVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

       if ($user && $user->otp !== null) {
    if (!$request->is('otp-verify', 'otp-resend')) {
        return redirect()->route('otp.verify');
    }
}


        return $next($request);
    }
}

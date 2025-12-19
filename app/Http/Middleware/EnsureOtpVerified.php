<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureOtpVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->otp !== null) {
            if (!$request->is('otp-verify')) {
                Auth::logout();
                return redirect('/login')->withErrors(['otp' => 'Unauthorized access! Please log in again.']);
            }
        }

        if ($user && $user->otp_expires_at && now()->greaterThan($user->otp_expires_at)) {
            Auth::logout();
            return redirect('/login')->withErrors(['otp' => 'OTP expired. Please log in again.']);
        }

        return $next($request);
    }
}

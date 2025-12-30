<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class UserControllers extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $otp = rand(100000, 999999);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'otp' => $otp,
        'otp_expires_at' => Carbon::now()->addMinutes(10),
    ]);

    // SEND OTP EMAIL (Brevo)
    $response = Http::withHeaders([
        'accept' => 'application/json',
        'api-key' => env('BREVO_API_KEY'),
        'Content-Type' => 'application/json',
    ])->post('https://api.brevo.com/v3/smtp/email', [
        'sender' => [
            'name' => env('BREVO_SENDER_NAME'),
            'email' => env('BREVO_SENDER_EMAIL'),
        ],
        'to' => [
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ],
        'subject' => 'Your OTP Code',
        'htmlContent' => "
            <h2>Email Verification</h2>
            <p>Your OTP code is:</p>
            <h1>{$otp}</h1>
            <p>This code will expire in 10 minutes.</p>
        ",
    ]);

    if (!$response->successful()) {
        return back()->withErrors([
            'email' => 'Failed to send OTP. Please try again.',
        ]);
    }

    session(['otp_email' => $user->email]);

    return redirect()
        ->route('verify-otp')
        ->with('success', 'Registration successful! Please check your email for the OTP.');
}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
'email' => 'required|email',
'password' => 'required|string',
        ]);


        $user = User::where('email', $request->email)->first();
if (!$user || !Hash::check($request->password, $user->password)) {
    return back()->withErrors(['email' => 'The provided credentials are incorrect.']);
}

        if (!$user -> email_verified_at) {
            session(['otp_email' => $user->email]);
return redirect()
                ->route('verify-otp')
                ->with('error', 'Please verify your email first. An OTP code has been sent to your email.If you did not receive it, please request a new one.');
        }
      Auth::login($user);
        return redirect()->route('home')->with('success', 'Login successful!');
    }
 public function home()
    {
        return view('auth.home');
    }
public function logout(Request $request)
    {
Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }

     public function showVerifyForm(Request $request)
    {
        $email = session('otp_email');
        return view('auth.verify-otp', compact('email'));
    }


public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required|digits:6',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'User not found.']);
    }

    // Check if user has too many OTP attempts
    if ($user->otp_attempts >= 5) {
        return back()->withErrors([
            'otp' => 'You have exceeded the maximum number of OTP attempts. Please request a new OTP.'
        ]);
    }

    // Check OTP expiration
    if (Carbon::now()->isAfter($user->otp_expires_at)) {
        return back()->withErrors([
            'otp' => 'The provided OTP has expired. Please request a new one.'
        ]);
    }

    // Check OTP correctness
    if ($user->otp !== $request->otp) {
        // Increment OTP attempts
        $user->increment('otp_attempts');

        return back()->withErrors([
            'otp' => 'The provided OTP is incorrect.'
        ]);
    }

    // OTP correct → reset OTP, attempts, and mark verified
    $user->update([
        'email_verified_at' => Carbon::now(),
        'otp' => null,
        'otp_expires_at' => null,
        'otp_attempts' => 0,
    ]);

    // Login the user
    Auth::login($user);

    return redirect()->route('home')->with('success', 'OTP verified successfully!');
}

public function resendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    // Check cooldown: 60 seconds
    if ($user->last_otp_sent_at && Carbon::parse($user->last_otp_sent_at)->diffInSeconds(now()) < 60) {
        $wait = 60 - Carbon::parse($user->last_otp_sent_at)->diffInSeconds(now());
        return back()->withErrors([
            'otp' => "Please wait {$wait} seconds before requesting a new OTP."
        ]);
    }

    $otp = rand(100000, 999999);

    $user->update([
        'otp' => $otp,
        'otp_expires_at' => Carbon::now()->addMinutes(10),
        'last_otp_sent_at' => Carbon::now(), // update timestamp
    ]);

    // SEND OTP EMAIL (Brevo)
    $response = Http::withHeaders([
        'accept' => 'application/json',
        'api-key' => env('BREVO_API_KEY'),
        'Content-Type' => 'application/json',
    ])->post('https://api.brevo.com/v3/smtp/email', [
        'sender' => [
            'name' => env('BREVO_SENDER_NAME'),
            'email' => env('BREVO_SENDER_EMAIL'),
        ],
        'to' => [
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ],
        'subject' => 'Your OTP Code',
        'htmlContent' => "
            <h2>Resent OTP Code</h2>
            <p>Your new OTP code is:</p>
            <h1>{$otp}</h1>
            <p>This code will expire in 10 minutes.</p>
        ",
    ]);

    if (!$response->successful()) {
        return back()->withErrors([
            'email' => 'Failed to resend OTP. Please try again.',
        ]);
    }

    session(['otp_email' => $user->email]);

    return back()->with('success', 'A new OTP has been sent to your email.');
}
public function showForgotPasswordForm()
{
    return view('auth.forgot-password');
}

public function sendForgotPasswordOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    // OTP cooldown (optional for forgot password too)
    if ($user->last_otp_sent_at && Carbon::parse($user->last_otp_sent_at)->diffInSeconds(now()) < 60) {
        $wait = 60 - Carbon::parse($user->last_otp_sent_at)->diffInSeconds(now());
        return back()->withErrors([
            'otp' => "Please wait {$wait} seconds before requesting a new OTP."
        ]);
    }

    $otp = rand(100000, 999999);

    $user->update([
        'otp' => $otp,
        'otp_expires_at' => Carbon::now()->addMinutes(10),
        'last_otp_sent_at' => Carbon::now(),
    ]);

    // Send email (same Brevo)
    $response = Http::withHeaders([
        'accept' => 'application/json',
        'api-key' => env('BREVO_API_KEY'),
        'Content-Type' => 'application/json',
    ])->post('https://api.brevo.com/v3/smtp/email', [
        'sender' => [
            'name' => env('BREVO_SENDER_NAME'),
            'email' => env('BREVO_SENDER_EMAIL'),
        ],
        'to' => [
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ],
        'subject' => 'Password Reset OTP',
        'htmlContent' => "
            <h2>Password Reset OTP</h2>
            <p>Your OTP code is:</p>
            <h1>{$otp}</h1>
            <p>This code will expire in 10 minutes.</p>
        ",
    ]);

    if (!$response->successful()) {
        return back()->withErrors([
            'email' => 'Failed to send OTP. Please try again.',
        ]);
    }

session(['forgot_password_otp_email' => $user->email]);
return redirect()->route('forgot-password-otp-verify')
    ->with('success', 'Check your email for the OTP to reset your password.');


}

public function verifyForgotPasswordOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'otp' => 'required|digits:6',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'User not found.']);
    }

    if ($user->otp_attempts >= 5) {
        return back()->withErrors(['otp' => 'Too many attempts. Request a new OTP.']);
    }

    if (Carbon::now()->isAfter($user->otp_expires_at)) {
        return back()->withErrors(['otp' => 'OTP expired. Request a new OTP.']);
    }

    if ($user->otp !== $request->otp) {
        $user->increment('otp_attempts');
        return back()->withErrors(['otp' => 'Incorrect OTP.']);
    }

    // OTP correct → reset OTP and attempts
    $user->update([
        'otp' => null,
        'otp_expires_at' => null,
        'otp_attempts' => 0,
    ]);

    session(['reset_email' => $user->email]);

    // Redirect to reset password page
    return redirect()->route('reset-password.form')->with('success', 'OTP verified. You can now reset your password.');
}


public function showResetPasswordForm()
{
    return view('auth.reset-password'); // your blade
}

public function resetPassword(Request $request)
{
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    $email = session('reset_email');
    if (!$email) {
        return redirect()->route('forgot-password')->withErrors(['email' => 'Session expired. Try again.']);
    }

    $user = User::where('email', $email)->first();
    if (!$user) {
        return redirect()->route('forgot-password')->withErrors(['email' => 'User not found.']);
    }

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    session()->forget('reset_email');

    return redirect()->route('login')->with('success', 'Password changed successfully. You can now login.');
}

public function showForgotPasswordOtpForm()
{
    $email = session('forgot_password_otp_email'); // <-- make sure session exists
    return view('auth.forgot-password-otp-verify', compact('email'));
}



}
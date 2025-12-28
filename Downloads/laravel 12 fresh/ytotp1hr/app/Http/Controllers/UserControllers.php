<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Container\Attributes\Log;
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp' => rand(100000, 999999),
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

     $user = User::where('email', $request->email)->first();

        session(['otp_email' => $user->email]);

        return redirect()
            ->route('verify.otp')
            ->with('success', 'Registration successful! Please check your email for the OTP code.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);


        $user = User::where('email', $request->email)->first();
        if ($user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'The provided credentials are incorrect.']);
        }
        if (!$user -> email_verified_at) {
            session(['otp_email' => $user->email]);
            return requiredirect()
                ->route('verify.otp')
                ->with('error', 'Please verify your email first. An OTP code has been sent to your email.If you did not receive it, please request a new one.');
        }
      Auth::login($user);
        return redirect()->route('/home')->with('success', 'Login successful!');
    }
 public function home()
    {
        return view('auth.home');
    }
     public function logout()
    {
Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
        return redirect()->route('/login')->with('success', 'Logged out successfully!');
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
      if($user->otp !== $request->otp){
          return back()->withErrors(['otp' => 'The provided OTP is incorrect.']);
      }
        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'The provided OTP has expired. Please request a new one.']);
        }
       $user->update([
           'email_verified_at' => Carbon::now(),
           'otp' => null,
           'otp_expires_at' => null,]);

        Auth::login($user);
           return redirect();
                 return redirect()->route('/home')->with('success', 'otp verified successful!');

    }
  public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users'
        ]);
        $user = User::where('email', $request->email)->first();
  $otp = rand(100000, 999999);
        $user ->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);


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
                <html>
                    <body>
                        <h1>Your OTP Code</h1>
                        <p>Your OTP code is: <strong>{$user->otp}</strong></p>
                        <p>This code will expire in 10 minutes.</p>
                    </body>
                </html>
            ",
        ]);
        if (!$response->successful()){
            Log::error("Otp failed");
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again']);
        }
        session(['otp_email' => $user->email]);
        return back()
            ->with('success', 'A new OTP code has been sent to your email.');
    }}
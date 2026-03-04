<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('profiles');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_type' => 'required|in:advertiser,vendor',
        ]);

        $user = \App\Models\User::create([
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'status' => 'suspended', // Suspended until verified
        ]);

        \Illuminate\Support\Facades\Log::info('New user registered: ' . $user->email . ' as ' . $request->user_type);

        // Automatically create a profile based on selection
        $profile = \App\Models\Profile::create([
            'user_id' => $user->id,
            'profile_type' => $request->user_type,
            'display_name' => $request->name,
            'status' => 'pending',
        ]);

        // Generate and send verification code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        \App\Models\UserVerification::create([
            'user_id' => $user->id,
            'verification_type' => 'email',
            'verification_code' => $code,
            'status' => 'pending',
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(15),
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerificationEmail($code, $request->name));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Registration mail failed for user ' . $user->id . ': ' . $e->getMessage(), [
                'exception' => $e
            ]);
            // Still set session so they can try to resend from the verify page
            session(['verify_user_id' => $user->id]);
            return redirect()->route('verification.notice')->withErrors(['code' => 'Registration successful, but we couldn\'t send the verification email. Please try the Resend button. Error: ' . $e->getMessage()]);
        }

        // Store user ID in session for verification (temporary authentication)
        session(['verify_user_id' => $user->id]);

        return redirect()->route('verification.notice');
    }

    public function showVerifyForm()
    {
        if (!session('verify_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.verify');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $userId = session('verify_user_id');
        if (!$userId)
            return redirect()->route('login');

        $verification = \App\Models\UserVerification::where('user_id', $userId)
            ->where('verification_type', 'email')
            ->where('verification_code', $request->code)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $user = \App\Models\User::find($userId);
        $user->update([
            'status' => 'active',
            'email_verified_at' => now()
        ]);

        // Update profile status as well
        $user->profiles()->update(['status' => 'active']);

        $verification->update([
            'status' => 'verified',
            'verified_at' => now()
        ]);

        // Automatically set the first profile as current
        $profile = $user->profiles()->first();
        if ($profile) {
            session([
                'current_profile_id' => $profile->id,
                'current_profile_type' => $profile->profile_type,
            ]);
        }

        Auth::login($user);
        session()->forget('verify_user_id');

        return redirect()->route('dashboard');
    }

    public function resendCode()
    {
        $userId = session('verify_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            session()->forget('verify_user_id');
            return redirect()->route('login');
        }

        $profile = $user->profiles()->first();
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update existing pending verification or create new one
        $verification = \App\Models\UserVerification::where('user_id', $userId)
            ->where('verification_type', 'email')
            ->where('status', 'pending')
            ->first();

        if ($verification) {
            $verification->update([
                'verification_code' => $code,
                'last_sent_at' => now(),
                'expires_at' => now()->addMinutes(15),
            ]);
        } else {
            \App\Models\UserVerification::create([
                'user_id' => $user->id,
                'verification_type' => 'email',
                'verification_code' => $code,
                'status' => 'pending',
                'last_sent_at' => now(),
                'expires_at' => now()->addMinutes(15),
            ]);
        }

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerificationEmail($code, $profile ? $profile->display_name : 'User'));
        } catch (\Throwable $e) {
            // Log full error details for debugging
            \Illuminate\Support\Facades\Log::error('Mail sending failed for user ' . $user->id . ': ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->withErrors(['code' => 'Verification email send failed. Please ensure all files (app/Mail/VerificationEmail.php and emails.verification-code view) are uploaded to the server. Original error: ' . $e->getMessage()]);
        }

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Generate a random token
        $token = \Illuminate\Support\Str::random(64);

        // Store in user_verifications with 'identity' type as a workaround for password reset
        \App\Models\UserVerification::updateOrCreate(
            ['user_id' => $user->id, 'verification_type' => 'identity'],
            [
                'verification_code' => $token,
                'status' => 'pending',
                'expires_at' => now()->addHours(1),
            ]
        );

        $profile = $user->profiles()->first();

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ForgotPasswordEmail($token, $profile ? $profile->display_name : 'User'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Forgot password mail failed for user ' . $user->id . ': ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send reset link. Please try again later.']);
        }

        return back()->with('status', 'We have emailed your password reset link!');
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        $verification = \App\Models\UserVerification::where('verification_code', $token)
            ->where('verification_type', 'identity')
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return redirect()->route('password.request')->withErrors(['email' => 'This password reset token is invalid or has expired.']);
        }

        $user = \App\Models\User::find($verification->user_id);

        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $user->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $verification = \App\Models\UserVerification::where('verification_code', $request->token)
            ->where('verification_type', 'identity')
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['email' => 'This password reset token is invalid or has expired.']);
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || $user->id != $verification->user_id) {
            return back()->withErrors(['email' => 'Invalid reset attempt.']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        $verification->update(['status' => 'verified', 'verified_at' => now()]);

        return redirect()->route('login')->with('status', 'Your password has been reset!');
    }
}

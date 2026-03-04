<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'status' => 'active',
            ]);

            // Log the user in
            Auth::login($user, $request->remember ?? false);

            // Create token for API usage
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return response()->json([
                'message' => 'Registration successful',
                'user' => $user->load('profiles'),
                'token' => $token,
                'has_profiles' => $user->profiles()->count() > 0,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user and create token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user account is active
        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Your account is not active.',
                'status' => $user->status,
            ], 403);
        }

        // Check if 2FA is enabled
        if ($user->two_factor_enabled) {
            // Store user ID in session for 2FA verification
            session(['2fa_user_id' => $user->id, '2fa_remember' => $request->remember ?? false]);
            
            return response()->json([
                'message' => '2FA verification required',
                'requires_2fa' => true,
            ], 200);
        }

        // Update last login info
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Log the user in
        Auth::login($user, $request->remember ?? false);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->load('profiles'),
            'token' => $token,
            'has_profiles' => $user->profiles()->count() > 0,
            'current_profile' => session('current_profile_id'),
        ], 200);
    }

    /**
     * Get the authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('profiles');
        
        return response()->json([
            'user' => $user,
            'current_profile_id' => session('current_profile_id'),
            'current_profile' => $user->profiles()->find(session('current_profile_id')),
        ], 200);
    }

    /**
     * Logout user (revoke token).
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke the user's current token
        $request->user()->currentAccessToken()->delete();

        // Clear session
        Auth::logout();
        session()->forget('current_profile_id');
        session()->invalidate();
        session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    /**
     * Logout from all devices (revoke all tokens).
     */
    public function logoutAll(Request $request): JsonResponse
    {
        // Revoke all tokens
        $request->user()->tokens()->delete();

        // Clear session
        Auth::logout();
        session()->forget('current_profile_id');
        session()->invalidate();
        session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out from all devices successfully',
        ], 200);
    }

    /**
     * Refresh the user's token.
     */
    public function refresh(Request $request): JsonResponse
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        // Create new token
        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $token,
        ], 200);
    }
}

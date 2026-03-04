<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TwoFactorRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TwoFactorController extends Controller
{
    /**
     * Enable 2FA for the authenticated user.
     */
    public function enable(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->two_factor_enabled) {
            return response()->json([
                'message' => '2FA is already enabled',
            ], 400);
        }

        // Generate a simple 6-digit secret (in production, use Google Authenticator libraries)
        $secret = $this->generateSecret();
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $recoveryCodes,
            'two_factor_enabled' => true,
        ]);

        return response()->json([
            'message' => '2FA enabled successfully',
            'secret' => $secret,
            'recovery_codes' => $recoveryCodes,
        ], 200);
    }

    /**
     * Disable 2FA for the authenticated user.
     */
    public function disable(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->two_factor_enabled) {
            return response()->json([
                'message' => '2FA is not enabled',
            ], 400);
        }

        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_enabled' => false,
        ]);

        return response()->json([
            'message' => '2FA disabled successfully',
        ], 200);
    }

    /**
     * Verify 2FA code during login.
     */
    public function verify(TwoFactorRequest $request): JsonResponse
    {
        $userId = session('2fa_user_id');
        $remember = session('2fa_remember', false);

        if (!$userId) {
            return response()->json([
                'message' => 'No 2FA verification pending',
            ], 400);
        }

        $user = \App\Models\User::findOrFail($userId);

        // Verify the code (simple implementation - in production use TOTP libraries)
        $isValid = $this->verifyCode($user->two_factor_secret, $request->code);

        // Also check recovery codes
        if (!$isValid) {
            $isValid = $this->verifyRecoveryCode($user, $request->code);
        }

        if (!$isValid) {
            return response()->json([
                'message' => 'Invalid 2FA code',
            ], 401);
        }

        // Update last login info
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Log the user in
        Auth::login($user, $remember);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Clear 2FA session data
        session()->forget(['2fa_user_id', '2fa_remember']);

        return response()->json([
            'message' => '2FA verification successful',
            'user' => $user->load('profiles'),
            'token' => $token,
            'has_profiles' => $user->profiles()->count() > 0,
        ], 200);
    }

    /**
     * Get current 2FA status.
     */
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'two_factor_enabled' => $user->two_factor_enabled,
        ], 200);
    }

    /**
     * Regenerate recovery codes.
     */
    public function regenerateRecoveryCodes(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->two_factor_enabled) {
            return response()->json([
                'message' => '2FA is not enabled',
            ], 400);
        }

        $recoveryCodes = $this->generateRecoveryCodes();

        $user->update([
            'two_factor_recovery_codes' => $recoveryCodes,
        ]);

        return response()->json([
            'message' => 'Recovery codes regenerated successfully',
            'recovery_codes' => $recoveryCodes,
        ], 200);
    }

    /**
     * Generate a secret for 2FA.
     * 
     * NOTE: This is a simplified implementation.
     * In production, use packages like:
     * - pragmarx/google2fa
     * - spomky-labs/otphp
     */
    private function generateSecret(): string
    {
        return strtoupper(Str::random(16));
    }

    /**
     * Generate recovery codes.
     */
    private function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(Str::random(10));
        }
        return $codes;
    }

    /**
     * Verify 2FA code.
     * 
     * NOTE: This is a placeholder implementation.
     * In production, implement TOTP verification using libraries like pragmarx/google2fa.
     */
    private function verifyCode(string $secret, string $code): bool
    {
        // Placeholder: In production, use TOTP algorithm
        // For now, accept the secret itself as a valid code for testing
        return $code === substr($secret, 0, 6);
    }

    /**
     * Verify recovery code and mark it as used.
     */
    private function verifyRecoveryCode(\App\Models\User $user, string $code): bool
    {
        $recoveryCodes = $user->two_factor_recovery_codes ?? [];

        $codeIndex = array_search(strtoupper($code), $recoveryCodes);

        if ($codeIndex === false) {
            return false;
        }

        // Remove the used recovery code
        unset($recoveryCodes[$codeIndex]);
        $user->update([
            'two_factor_recovery_codes' => array_values($recoveryCodes),
        ]);

        return true;
    }
}

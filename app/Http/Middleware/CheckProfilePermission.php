<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Profile;

class CheckProfilePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission  The permission slug to check
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Ensure user is authenticated
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        // Get current profile ID from session
        $currentProfileId = session('current_profile_id');

        if (!$currentProfileId) {
            return response()->json([
                'message' => 'No profile selected. Please select a profile first.',
                'requires_profile_selection' => true,
            ], 403);
        }

        // Get the profile
        $profile = $request->user()->profiles()->find($currentProfileId);

        if (!$profile) {
            // Clear invalid profile from session
            session()->forget('current_profile_id');
            
            return response()->json([
                'message' => 'Invalid profile. Please select a valid profile.',
                'requires_profile_selection' => true,
            ], 403);
        }

        // Check if profile is active
        if ($profile->status !== 'active') {
            return response()->json([
                'message' => 'Your profile is not active.',
                'profile_status' => $profile->status,
            ], 403);
        }

        // Check if profile has the required permission
        $hasPermission = $profile->permissions()
            ->whereHas('permission', function ($query) use ($permission) {
                $query->where('slug', $permission);
            })
            ->whereNull('revoked_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();

        if (!$hasPermission) {
            return response()->json([
                'message' => 'Insufficient permissions.',
                'required_permission' => $permission,
            ], 403);
        }

        // Add current profile to request for easy access in controllers
        $request->merge(['current_profile' => $profile]);

        return $next($request);
    }
}

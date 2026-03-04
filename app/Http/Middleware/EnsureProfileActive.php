<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileActive
{
    /**
     * Handle an incoming request.
     *
     * Ensures that the user has selected an active profile before accessing protected routes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
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
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'No profile selected. Please select a profile first.',
                    'requires_profile_selection' => true,
                    'available_profiles' => $request->user()->profiles()->select('id', 'profile_type', 'display_name', 'status')->get(),
                ], 403);
            }

            return redirect()->route('profiles.index')->with('error', 'Please select a profile first.');
        }

        // Get the profile
        $profile = $request->user()->profiles()->find($currentProfileId);

        if (!$profile) {
            // Clear invalid profile from session
            session()->forget('current_profile_id');

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Invalid profile. Please select a valid profile.',
                    'requires_profile_selection' => true,
                    'available_profiles' => $request->user()->profiles()->select('id', 'profile_type', 'display_name', 'status')->get(),
                ], 403);
            }

            return redirect()->route('profiles.index')->with('error', 'Invalid profile selected.');
        }

        // Check if profile is active
        if ($profile->status !== 'active') {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Your current profile is not active.',
                    'profile_status' => $profile->status,
                    'profile_id' => $profile->id,
                ], 403);
            }

            return redirect()->route('profiles.index')->with('error', 'Your current profile is not active.');
        }

        // Add current profile to request for easy access in controllers
        $request->merge(['current_profile' => $profile]);

        // Ensure session stays in sync
        session([
            'current_profile_id' => $profile->id,
            'current_profile_type' => $profile->profile_type,
        ]);

        return $next($request);
    }
}

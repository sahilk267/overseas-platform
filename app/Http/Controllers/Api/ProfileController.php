<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileSwitchRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Get all profiles for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $profiles = $request->user()->profiles()
            ->with(['location', 'permissions.permission'])
            ->get();

        return response()->json([
            'profiles' => $profiles,
            'current_profile_id' => session('current_profile_id'),
        ], 200);
    }

    /**
     * Get the current active profile.
     */
    public function current(Request $request): JsonResponse
    {
        $currentProfileId = session('current_profile_id');

        if (!$currentProfileId) {
            return response()->json([
                'message' => 'No profile selected',
                'current_profile' => null,
            ], 200);
        }

        $profile = $request->user()->profiles()
            ->with(['location', 'permissions.permission'])
            ->find($currentProfileId);

        if (!$profile) {
            // Clear invalid profile from session
            session()->forget('current_profile_id');
            
            return response()->json([
                'message' => 'Current profile not found',
                'current_profile' => null,
            ], 404);
        }

        return response()->json([
            'current_profile' => $profile,
        ], 200);
    }

    /**
     * Switch to a different profile.
     */
    public function switch(ProfileSwitchRequest $request): JsonResponse
    {
        $profile = $request->user()->profiles()
            ->with(['location', 'permissions.permission'])
            ->findOrFail($request->profile_id);

        // Store the profile ID in session
        session(['current_profile_id' => $profile->id]);

        // Log activity
        \App\Models\ActivityLog::create([
            'profile_id' => $profile->id,
            'action' => 'profile_switched',
            'entity_type' => 'profile',
            'entity_id' => $profile->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'user_id' => $request->user()->id,
                'profile_type' => $profile->profile_type,
            ],
        ]);

        return response()->json([
            'message' => 'Profile switched successfully',
            'current_profile' => $profile,
        ], 200);
    }

    /**
     * Get a specific profile by ID.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $profile = $request->user()->profiles()
            ->with(['location', 'permissions.permission'])
            ->findOrFail($id);

        return response()->json([
            'profile' => $profile,
        ], 200);
    }

    /**
     * Get permissions for a specific profile.
     */
    public function permissions(Request $request, int $id): JsonResponse
    {
        $profile = $request->user()->profiles()->findOrFail($id);

        $permissions = $profile->permissions()
            ->with('permission')
            ->whereNull('revoked_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get()
            ->map(function ($profilePermission) {
                return [
                    'id' => $profilePermission->permission->id,
                    'name' => $profilePermission->permission->name,
                    'slug' => $profilePermission->permission->slug,
                    'category' => $profilePermission->permission->category,
                    'granted_at' => $profilePermission->granted_at,
                    'expires_at' => $profilePermission->expires_at,
                ];
            });

        return response()->json([
            'profile_id' => $profile->id,
            'permissions' => $permissions,
        ], 200);
    }

    /**
     * Check if current profile has a specific permission.
     */
    public function checkPermission(Request $request): JsonResponse
    {
        $request->validate([
            'permission' => 'required|string',
        ]);

        $currentProfileId = session('current_profile_id');

        if (!$currentProfileId) {
            return response()->json([
                'has_permission' => false,
                'message' => 'No profile selected',
            ], 200);
        }

        $profile = $request->user()->profiles()->find($currentProfileId);

        if (!$profile) {
            return response()->json([
                'has_permission' => false,
                'message' => 'Profile not found',
            ], 404);
        }

        $hasPermission = $profile->permissions()
            ->whereHas('permission', function ($query) use ($request) {
                $query->where('slug', $request->permission);
            })
            ->whereNull('revoked_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();

        return response()->json([
            'has_permission' => $hasPermission,
            'profile_id' => $profile->id,
            'permission' => $request->permission,
        ], 200);
    }
}

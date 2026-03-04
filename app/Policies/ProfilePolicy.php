<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfilePolicy
{
    /**
     * Determine whether the user can view any profiles.
     */
    public function viewAny(User $user): bool
    {
        // Users can view their own profiles
        return true;
    }

    /**
     * Determine whether the user can view the profile.
     */
    public function view(User $user, Profile $profile): bool
    {
        // Users can view their own profiles
        // Admins can view any profile
        return $user->id === $profile->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create profiles.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create profiles for themselves
        return true;
    }

    /**
     * Determine whether the user can update the profile.
     */
    public function update(User $user, Profile $profile): bool
    {
        // Users can update their own profiles
        // Admins can update any profile
        return $user->id === $profile->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the profile.
     */
    public function delete(User $user, Profile $profile): bool
    {
        // Users can delete their own profiles (soft delete)
        // Admins can delete any profile
        return $user->id === $profile->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the profile.
     */
    public function restore(User $user, Profile $profile): bool
    {
        // Users can restore their own profiles
        // Admins can restore any profile
        return $user->id === $profile->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the profile.
     */
    public function forceDelete(User $user, Profile $profile): bool
    {
        // Only admins can permanently delete profiles
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can approve the profile.
     */
    public function approve(User $user, Profile $profile): bool
    {
        // Only admins can approve profiles
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject the profile.
     */
    public function reject(User $user, Profile $profile): bool
    {
        // Only admins can reject profiles
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can grant permissions to the profile.
     */
    public function grantPermissions(User $user, Profile $profile): bool
    {
        // Only admins can grant permissions
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can revoke permissions from the profile.
     */
    public function revokePermissions(User $user, Profile $profile): bool
    {
        // Only admins can revoke permissions
        return $user->isAdmin();
    }
}

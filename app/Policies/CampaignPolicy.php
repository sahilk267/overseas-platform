<?php

namespace App\Policies;

use App\Models\AdCampaign;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CampaignPolicy
{
    /**
     * Determine whether the user can view any campaigns.
     */
    public function viewAny(User $user): bool
    {
        // Users with active profile can view campaigns
        $profile = $user->activeProfile();
        
        return $profile && $profile->isActive();
    }

    /**
     * Determine whether the user can view the campaign.
     */
    public function view(User $user, AdCampaign $adCampaign): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile) {
            return false;
        }

        // Owner can view
        if ($adCampaign->advertiser_profile_id === $profile->id) {
            return true;
        }

        // Admins can view any campaign
        if ($profile->isAdmin()) {
            return true;
        }

        // Users with view_campaigns permission can view
        return $profile->hasPermission('view_campaigns');
    }

    /**
     * Determine whether the user can create campaigns.
     */
    public function create(User $user): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile || !$profile->isActive()) {
            return false;
        }

        // Advertisers and admins can create campaigns
        return $profile->isType('advertiser') || $profile->isAdmin() || $profile->hasPermission('create_campaigns');
    }

    /**
     * Determine whether the user can update the campaign.
     */
    public function update(User $user, AdCampaign $adCampaign): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile) {
            return false;
        }

        // Owner can update (if not in certain statuses)
        if ($adCampaign->advertiser_profile_id === $profile->id) {
            // Cannot edit completed or cancelled campaigns
            return !in_array($adCampaign->status, ['completed', 'cancelled']);
        }

        // Admins can update any campaign
        return $profile->isAdmin();
    }

    /**
     * Determine whether the user can delete the campaign.
     */
    public function delete(User $user, AdCampaign $adCampaign): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile) {
            return false;
        }

        // Owner can delete (only drafts)
        if ($adCampaign->advertiser_profile_id === $profile->id) {
            return $adCampaign->status === 'draft';
        }

        // Admins can delete any campaign
        return $profile->isAdmin();
    }

    /**
     * Determine whether the user can restore the campaign.
     */
    public function restore(User $user, AdCampaign $adCampaign): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile) {
            return false;
        }

        // Owner can restore
        if ($adCampaign->advertiser_profile_id === $profile->id) {
            return true;
        }

        // Admins can restore any campaign
        return $profile->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the campaign.
     */
    public function forceDelete(User $user, AdCampaign $adCampaign): bool
    {
        $profile = $user->activeProfile();
        
        // Only admins can permanently delete
        return $profile && $profile->isAdmin();
    }

    /**
     * Determine whether the user can approve the campaign.
     */
    public function approve(User $user, AdCampaign $adCampaign): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile) {
            return false;
        }

        // Only admins or users with approve_campaigns permission can approve
        return $profile->isAdmin() || $profile->hasPermission('approve_campaigns');
    }
}

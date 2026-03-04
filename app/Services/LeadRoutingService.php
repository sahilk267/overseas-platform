<?php

namespace App\Services;

use App\Models\AdCampaign;
use App\Models\Profile;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class LeadRoutingService
{
    /**
     * Finds the nearest agencies capable of handling a campaign's category.
     * 
     * @param AdCampaign $campaign
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function findNearestAgencies(AdCampaign $campaign, $limit = 5)
    {
        $category_id = $campaign->category_id;
        $clientProfile = $campaign->advertiser;

        $campaignCategory = \App\Models\AdCategory::find($category_id);
        $categoryIds = [$category_id];
        if ($campaignCategory && $campaignCategory->parent_id) {
            $categoryIds[] = $campaignCategory->parent_id;
        }

        if (!$clientProfile || !$clientProfile->location_id || !$clientProfile->location) {
            // Fallback: Find any active agency in that category or parent category
            return Profile::where('profile_type', 'vendor')
                ->where('status', 'active')
                ->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('ad_categories.id', $categoryIds);
                })
                ->limit($limit)
                ->get();
        }

        $clientLoc = $clientProfile->location;
        $lat = $clientLoc->latitude;
        $lon = $clientLoc->longitude;

        // Use Haversine formula to find nearest agencies
        $candidates = Profile::where('profile_type', 'vendor')
            ->where('profiles.status', 'active')
            ->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('ad_categories.id', $categoryIds);
            })
            ->join('locations', 'profiles.location_id', '=', 'locations.id')
            ->select('profiles.*', DB::raw("
                (6371 * acos(cos(radians($lat)) 
                * cos(radians(locations.latitude)) 
                * cos(radians(locations.longitude) - radians($lon)) 
                + sin(radians($lat)) 
                * sin(radians(locations.latitude)))) AS distance
            "))
            ->orderBy('distance', 'asc')
            ->limit($limit)
            ->get();

        // BROAD MATCH FALLBACK: If we have very few matches, include other active agencies even if category is missing
        if ($candidates->count() < 2) {
            $extraNeeded = $limit - $candidates->count();
            $broadCandidates = Profile::where('profile_type', 'vendor')
                ->where('profiles.status', 'active')
                ->whereNotIn('id', $candidates->pluck('id'))
                ->limit($extraNeeded)
                ->get();

            $candidates = $candidates->merge($broadCandidates);
        }

        return $candidates;
    }

    /**
     * Notifies the next agency in line for a campaign.
     * 
     * @param AdCampaign $campaign
     */
    public function routeToNextAgency(AdCampaign $campaign)
    {
        // Logic to track who has already 'passed' and notify the next one
        // This will be implemented with a notifications table tracking.
    }
}

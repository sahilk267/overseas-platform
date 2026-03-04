<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Profile;
use App\Models\AdExecution;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::where('status', 'approved')->get();
        $executions = AdExecution::where('status', 'completed')->get();

        if ($profiles->count() < 2) {
            $this->command->warn('Skipping ReviewSeeder: Not enough profiles');
            return;
        }

        // Create profile reviews
        foreach ($profiles->take(10) as $reviewedProfile) {
            $reviewerProfiles = $profiles->where('id', '!=', $reviewedProfile->id)->random(rand(1, 3));

            foreach ($reviewerProfiles as $reviewer) {
                Review::create([
                    'reviewer_profile_id' => $reviewer->id,
                    'reviewed_profile_id' => $reviewedProfile->id,
                    'related_type' => null,
                    'related_id' => null,
                    'rating' => fake()->numberBetween(1, 5),
                    'comment' => fake()->optional(0.7)->paragraph(),
                    'is_verified' => fake()->boolean(70),
                ]);
            }
        }

        // Create execution reviews
        foreach ($executions->take(5) as $execution) {
            $advertiser = $execution->campaign->advertiserProfile;
            $vendor = $execution->inventory->vendorProfile;

            // Advertiser reviews vendor
            Review::create([
                'reviewer_profile_id' => $advertiser->id,
                'reviewed_profile_id' => $vendor->id,
                'related_type' => 'ad_execution',
                'related_id' => $execution->id,
                'rating' => fake()->numberBetween(3, 5),
                'comment' => fake()->optional(0.6)->sentence(),
                'is_verified' => true,
            ]);
        }
    }
}

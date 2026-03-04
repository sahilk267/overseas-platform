<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\PromotionAssignment;
use App\Models\Profile;
use App\Models\AdCampaign;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::whereIn('profile_type', ['advertiser', 'vendor'])->get();
        $campaigns = AdCampaign::all();

        if ($profiles->isEmpty()) {
            $this->command->warn('Skipping PromotionSeeder: No profiles found');
            return;
        }

        foreach ($profiles->take(5) as $profile) {
            $startDate = fake()->dateTimeBetween('now', '+1 month');
            $endDate = fake()->dateTimeBetween($startDate, '+2 months');

            $promotion = Promotion::create([
                'profile_id' => $profile->id,
                'promotion_type' => fake()->randomElement(['featured', 'homepage_banner', 'category_spotlight', 'boost']),
                'title' => fake()->sentence(2),
                'description' => fake()->optional(0.6)->paragraph(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'budget' => fake()->randomFloat(2, 500, 5000),
                'currency' => 'USD',
                'status' => fake()->randomElement(['pending', 'active', 'completed']),
            ]);

            // Assign promotion to campaigns
            if ($profile->profile_type === 'advertiser' && $campaigns->isNotEmpty()) {
                $targetCampaigns = $campaigns->where('advertiser_profile_id', $profile->id)->take(2);
                
                foreach ($targetCampaigns as $campaign) {
                    PromotionAssignment::create([
                        'promotion_id' => $promotion->id,
                        'target_type' => 'ad_campaign',
                        'target_id' => $campaign->id,
                        'assigned_at' => now(),
                        'status' => 'active',
                        'cost' => fake()->randomFloat(2, 50, 500),
                        'currency' => 'USD',
                    ]);
                }
            }
        }
    }
}

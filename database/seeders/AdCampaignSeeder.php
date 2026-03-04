<?php

namespace Database\Seeders;

use App\Models\AdCampaign;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class AdCampaignSeeder extends Seeder
{
    public function run(): void
    {
        $advertiserProfiles = Profile::where('profile_type', 'advertiser')->get();
        $adminProfile = Profile::where('profile_type', 'admin')->first();

        if ($advertiserProfiles->isEmpty()) {
            $this->command->warn('Skipping AdCampaignSeeder: No advertiser profiles found');
            return;
        }

        // Create campaigns for each advertiser
        foreach ($advertiserProfiles as $advertiser) {
            $campaignCount = rand(2, 5);
            
            for ($i = 0; $i < $campaignCount; $i++) {
                $campaign = AdCampaign::factory()
                    ->for($advertiser, 'advertiserProfile')
                    ->create();

                // Approve some campaigns
                if (rand(0, 1)) {
                    $campaign->update([
                        'status' => 'approved',
                        'approved_by' => $adminProfile?->id,
                        'approved_at' => now(),
                    ]);
                }
            }
        }

        // Create additional random campaigns
        AdCampaign::factory(5)->create();
    }
}

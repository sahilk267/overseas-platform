<?php

namespace Database\Seeders;

use App\Models\TalentProfile;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class TalentProfileSeeder extends Seeder
{
    public function run(): void
    {
        $talentProfiles = Profile::where('profile_type', 'talent')->get();

        if ($talentProfiles->isEmpty()) {
            $this->command->warn('Skipping TalentProfileSeeder: No talent profiles found');
            return;
        }

        foreach ($talentProfiles as $profile) {
            TalentProfile::create([
                'profile_id' => $profile->id,
                'stage_name' => fake()->optional(0.7)->name(),
                'specialties' => fake()->randomElements(['singing', 'dancing', 'acting', 'comedy', 'music', 'theater'], rand(1, 3)),
                'experience_years' => fake()->numberBetween(0, 20),
                'hourly_rate' => fake()->randomFloat(2, 500, 5000),
                'currency' => 'USD',
                'available_for_hire' => true,
                'portfolio_description' => fake()->optional(0.6)->paragraph(),
                'languages' => fake()->randomElements(['English', 'Hindi', 'Spanish', 'French'], rand(1, 3)),
            ]);
        }
    }
}

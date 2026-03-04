<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        $advertiserProfiles = Profile::where('profile_type', 'advertiser')->get();
        $vendorProfiles = Profile::where('profile_type', 'vendor')->get();

        if ($advertiserProfiles->isEmpty() || $vendorProfiles->isEmpty()) {
            $this->command->warn('Skipping ContractSeeder: Missing profiles');
            return;
        }

        foreach ($advertiserProfiles->take(3) as $advertiser) {
            $vendor = $vendorProfiles->random();
            $startDate = fake()->dateTimeBetween('-1 month', 'now');
            $endDate = fake()->dateTimeBetween($startDate, '+6 months');

            Contract::create([
                'party_a_profile_id' => $advertiser->id,
                'party_b_profile_id' => $vendor->id,
                'contract_type' => 'ad_placement',
                'title' => fake()->sentence(3),
                'terms' => fake()->paragraph(5),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'value' => fake()->randomFloat(2, 5000, 50000),
                'currency' => 'USD',
                'status' => fake()->randomElement(['draft', 'pending_signature', 'active', 'completed']),
                'party_a_signed_at' => fake()->optional(0.7)->dateTime(),
                'party_b_signed_at' => fake()->optional(0.7)->dateTime(),
                'version' => 1,
            ]);
        }
    }
}

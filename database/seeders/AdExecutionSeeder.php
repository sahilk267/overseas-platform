<?php

namespace Database\Seeders;

use App\Models\AdExecution;
use App\Models\AdCampaign;
use App\Models\AdInventory;
use Illuminate\Database\Seeder;

class AdExecutionSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = AdCampaign::where('status', 'approved')->orWhere('status', 'active')->get();
        $inventory = AdInventory::where('status', 'active')->get();

        if ($campaigns->isEmpty() || $inventory->isEmpty()) {
            $this->command->warn('Skipping AdExecutionSeeder: Missing approved campaigns or active inventory');
            return;
        }

        // Create executions for approved/active campaigns
        foreach ($campaigns->take(10) as $campaign) {
            $executionCount = rand(1, 3);
            
            for ($i = 0; $i < $executionCount; $i++) {
                $selectedInventory = $inventory->random();
                $executionDate = fake()->dateTimeBetween($campaign->start_date, $campaign->end_date);
                $endDate = fake()->dateTimeBetween($executionDate, $campaign->end_date);
                
                $days = (int) $executionDate->diff($endDate)->days + 1;
                $cost = $selectedInventory->price_per_day * $days;

                AdExecution::create([
                    'campaign_id' => $campaign->id,
                    'inventory_id' => $selectedInventory->id,
                    'execution_date' => $executionDate,
                    'end_date' => $endDate,
                    'cost' => $cost,
                    'currency' => $selectedInventory->currency,
                    'status' => fake()->randomElement(['pending', 'confirmed', 'in_progress', 'completed']),
                    'notes' => fake()->optional(0.4)->sentence(),
                ]);
            }
        }
    }
}

<?php

namespace Database\Factories;

use App\Models\AdCampaign;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdCampaign>
 */
class AdCampaignFactory extends Factory
{
    protected $model = AdCampaign::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+3 months');

        return [
            'advertiser_profile_id' => Profile::factory()->advertiser(),
            'name' => fake()->sentence(3),
            'description' => fake()->optional(0.7)->paragraph(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'budget' => fake()->randomFloat(2, 1000, 50000),
            'currency' => 'USD',
            'status' => fake()->randomElement(['draft', 'pending_approval', 'approved', 'active', 'paused', 'completed', 'cancelled']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(30),
        ]);
    }
}

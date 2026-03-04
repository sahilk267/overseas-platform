<?php

namespace Database\Factories;

use App\Models\AdInventory;
use App\Models\Profile;
use App\Models\AdCategory;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdInventory>
 */
class AdInventoryFactory extends Factory
{
    protected $model = AdInventory::class;

    public function definition(): array
    {
        $inventoryType = fake()->randomElement(['billboard', 'digital_screen', 'banner', 'poster', 'vehicle']);

        return [
            'vendor_profile_id' => Profile::factory()->vendor(),
            'category_id' => AdCategory::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional(0.8)->paragraph(),
            'inventory_type' => $inventoryType,
            'dimensions' => fake()->randomElement(['10x20 feet', '5x10 feet', '20x40 feet', 'Full Wrap']),
            'location_id' => Location::factory(),
            'price_per_day' => fake()->randomFloat(2, 50, 5000),
            'currency' => 'USD',
            'min_booking_days' => fake()->numberBetween(1, 30),
            'requires_approval' => fake()->boolean(30),
            'status' => fake()->randomElement(['active', 'inactive', 'maintenance']),
        ];
    }
}

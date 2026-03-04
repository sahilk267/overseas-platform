<?php

namespace Database\Factories;

use App\Models\AdCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdCategory>
 */
class AdCategoryFactory extends Factory
{
    protected $model = AdCategory::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Billboards', 'Digital Screens', 'Banners', 'Posters', 'Vehicle Wraps',
            'Transit Advertising', 'Street Furniture', 'Airport Advertising', 'Mall Advertising'
        ]);

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => fake()->optional(0.6)->sentence(),
            'parent_id' => null,
        ];
    }
}

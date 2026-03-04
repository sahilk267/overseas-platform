<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $city = fake()->city();
        $state = fake()->optional()->state();
        $country = fake()->country();

        return [
            'name' => "$city, $country",
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'timezone' => fake()->timezone(),
        ];
    }
}

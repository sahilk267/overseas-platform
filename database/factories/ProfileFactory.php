<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        $profileType = fake()->randomElement(['advertiser', 'vendor', 'talent', 'event_organizer', 'admin']);

        return [
            'user_id' => User::factory(),
            'profile_type' => $profileType,
            'business_name' => $profileType !== 'talent' ? fake()->company() : null,
            'display_name' => fake()->name(),
            'bio' => fake()->sentence(10),
            'avatar' => fake()->optional(0.5)->imageUrl(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'location_id' => Location::factory(),
            'website' => fake()->optional(0.4)->url(),
            'social_links' => fake()->optional(0.3)->randomElement([
                ['facebook' => fake()->url(), 'twitter' => fake()->url()],
                ['instagram' => fake()->url(), 'linkedin' => fake()->url()],
                null,
            ]),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'suspended']),
            'rating' => fake()->randomFloat(2, 0, 5),
            'review_count' => fake()->numberBetween(0, 100),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
        'status' => 'active',
        'approved_at' => now(),
        ]);
    }

    public function advertiser(): static
    {
        return $this->state(fn(array $attributes) => [
        'profile_type' => 'advertiser',
        ]);
    }

    public function vendor(): static
    {
        return $this->state(fn(array $attributes) => [
        'profile_type' => 'vendor',
        ]);
    }

    public function talent(): static
    {
        return $this->state(fn(array $attributes) => [
        'profile_type' => 'talent',
        'business_name' => null,
        ]);
    }
}

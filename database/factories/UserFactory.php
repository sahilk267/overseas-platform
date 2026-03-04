<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate phone number matching constraint: ^[+]?[0-9]{10,15}$
        $phone = fake()->optional(0.7)->randomElement([
            '+' . fake()->numerify('##########'),      // +1234567890 (11 digits with +)
            '+' . fake()->numerify('############'),     // +12345678901 (12 digits with +)
            fake()->numerify('##########'),             // 1234567890 (10 digits)
            fake()->numerify('############'),          // 12345678901 (11 digits)
        ]);

        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'phone' => $phone,
            'status' => fake()->randomElement(['active', 'suspended']), // 'deleted' is handled by soft deletes
            'email_verified_at' => fake()->optional(0.8)->dateTime(),
            'phone_verified_at' => fake()->optional(0.6)->dateTime(),
            'two_factor_enabled' => false,
            'last_login_at' => fake()->optional(0.7)->dateTime(),
            'last_login_ip' => fake()->optional(0.7)->ipv4(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

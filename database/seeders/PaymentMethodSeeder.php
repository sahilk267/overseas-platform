<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::whereIn('profile_type', ['advertiser', 'vendor', 'talent'])->get();

        if ($profiles->isEmpty()) {
            $this->command->warn('Skipping PaymentMethodSeeder: No profiles found');
            return;
        }

        foreach ($profiles->take(10) as $profile) {
            PaymentMethod::create([
                'profile_id' => $profile->id,
                'method_type' => fake()->randomElement(['card', 'bank_account', 'paypal', 'wallet']),
                'provider' => fake()->randomElement(['stripe', 'paypal', 'razorpay', 'razorpay']),
                'provider_id' => fake()->uuid(),
                'last_four' => fake()->numerify('####'),
                'brand' => fake()->optional(0.6)->randomElement(['Visa', 'Mastercard', 'Amex']),
                'exp_month' => fake()->numberBetween(1, 12),
                'exp_year' => fake()->numberBetween(2025, 2030),
                'is_default' => true,
                'is_verified' => true,
            ]);
        }
    }
}

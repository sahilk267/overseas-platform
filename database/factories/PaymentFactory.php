<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Profile;
use App\Models\AdExecution;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 10, 10000);
        $fees = fake()->randomFloat(2, 0, $amount * 0.1); // Up to 10% fees

        return [
            'payer_profile_id' => Profile::factory(),
            'recipient_profile_id' => Profile::factory(),
            'execution_id' => AdExecution::factory(),
            'invoice_id' => null,
            'amount' => $amount,
            'fees' => $fees,
            // net_amount is generated - don't include
            'currency' => 'USD',
            'payment_method' => fake()->randomElement(['card', 'bank_transfer', 'paypal', 'wallet', 'cash']),
            'transaction_id' => fake()->optional(0.8)->uuid(),
            'idempotency_key' => fake()->optional(0.5)->uuid(),
            'status' => fake()->randomElement(['pending', 'completed', 'failed', 'refunded', 'partially_refunded']),
            'completed_at' => fake()->optional(0.6)->dateTime(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}

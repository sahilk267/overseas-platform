<?php

namespace Database\Seeders;

use App\Models\Refund;
use App\Models\Payment;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class RefundSeeder extends Seeder
{
    public function run(): void
    {
        $completedPayments = Payment::where('status', 'completed')->get();
        $adminUser = User::where('email', 'admin@umaep.com')->first();

        if ($completedPayments->isEmpty()) {
            $this->command->warn('Skipping RefundSeeder: No completed payments found');
            return;
        }

        // Create refunds for some completed payments
        foreach ($completedPayments->take(3) as $payment) {
            $refundAmount = fake()->randomFloat(2, 10, $payment->amount * 0.5); // Up to 50% refund

            Refund::create([
                'payment_id' => $payment->id,
                'requested_by' => $payment->payer_profile_id,
                'amount' => $refundAmount,
                'currency' => $payment->currency,
                'reason' => fake()->sentence(),
                'status' => fake()->randomElement(['pending', 'approved', 'completed']),
                'processed_by' => $adminUser?->id,
                'processed_at' => fake()->optional(0.6)->dateTime(),
                'transaction_id' => fake()->optional(0.5)->uuid(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Commission;
use App\Models\Payment;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class CommissionSeeder extends Seeder
{
    public function run(): void
    {
        $completedPayments = Payment::where('status', 'completed')->get();
        $adminProfile = Profile::where('profile_type', 'admin')->first();

        if ($completedPayments->isEmpty() || !$adminProfile) {
            $this->command->warn('Skipping CommissionSeeder: Missing payments or admin profile');
            return;
        }

        foreach ($completedPayments->take(10) as $payment) {
            $rate = fake()->randomFloat(2, 5, 15); // 5-15% commission
            $amount = $payment->amount * ($rate / 100);

            Commission::create([
                'payment_id' => $payment->id,
                'recipient_profile_id' => $adminProfile->id,
                'amount' => $amount,
                'currency' => $payment->currency,
                'rate' => $rate,
                'status' => fake()->randomElement(['pending', 'paid']),
                'paid_at' => fake()->optional(0.5)->dateTime(),
            ]);
        }
    }
}

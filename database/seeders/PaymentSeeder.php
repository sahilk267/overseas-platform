<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\AdExecution;
use App\Models\Invoice;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $executions = AdExecution::all();
        $invoices = Invoice::all();
        $advertiserProfiles = Profile::where('profile_type', 'advertiser')->get();
        $vendorProfiles = Profile::where('profile_type', 'vendor')->get();

        if ($advertiserProfiles->isEmpty() || $vendorProfiles->isEmpty()) {
            $this->command->warn('Skipping PaymentSeeder: Missing profiles');
            return;
        }

        // Create payments for executions
        foreach ($executions->take(10) as $execution) {
            $advertiser = $advertiserProfiles->random();
            $vendor = $execution->inventory->vendorProfile;

            Payment::create([
                'payer_profile_id' => $advertiser->id,
                'recipient_profile_id' => $vendor->id,
                'execution_id' => $execution->id,
                'invoice_id' => null,
                'amount' => $execution->cost,
                'fees' => $execution->cost * 0.05, // 5% platform fee
                'currency' => $execution->currency,
                'payment_method' => fake()->randomElement(['card', 'bank_transfer', 'paypal']),
                'transaction_id' => fake()->uuid(),
                'status' => fake()->randomElement(['completed', 'pending']),
                'completed_at' => fake()->optional(0.7)->dateTime(),
            ]);
        }

        // Create payments for invoices
        foreach ($invoices->take(5) as $invoice) {
            Payment::create([
                'payer_profile_id' => $invoice->recipient_profile_id,
                'recipient_profile_id' => $invoice->issuer_profile_id,
                'execution_id' => null,
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total,
                'fees' => $invoice->total * 0.03, // 3% platform fee
                'currency' => $invoice->currency,
                'payment_method' => fake()->randomElement(['card', 'bank_transfer']),
                'transaction_id' => fake()->uuid(),
                'status' => fake()->randomElement(['completed', 'pending', 'failed']),
                'completed_at' => fake()->optional(0.6)->dateTime(),
            ]);
        }
    }
}

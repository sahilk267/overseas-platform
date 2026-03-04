<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $vendorProfiles = Profile::where('profile_type', 'vendor')->get();
        $advertiserProfiles = Profile::where('profile_type', 'advertiser')->get();

        if ($vendorProfiles->isEmpty() || $advertiserProfiles->isEmpty()) {
            $this->command->warn('Skipping InvoiceSeeder: Missing vendor or advertiser profiles');
            return;
        }

        // Create invoices from vendors to advertisers
        foreach ($vendorProfiles->take(5) as $vendor) {
            $invoiceCount = rand(1, 3);
            
            for ($i = 0; $i < $invoiceCount; $i++) {
                $advertiser = $advertiserProfiles->random();
                
                $invoice = Invoice::factory()
                    ->for($vendor, 'issuerProfile')
                    ->for($advertiser, 'recipientProfile')
                    ->create();

                // Add line items
                $lineItemCount = rand(1, 4);
                $subtotal = 0;

                for ($j = 0; $j < $lineItemCount; $j++) {
                    $quantity = rand(1, 5);
                    $unitPrice = fake()->randomFloat(2, 50, 1000);
                    $lineTotal = $quantity * $unitPrice;
                    $subtotal += $lineTotal;

                    InvoiceLineItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => fake()->sentence(3),
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                    ]);
                }

                // Update invoice totals
                $tax = $subtotal * 0.1; // 10% tax
                $total = $subtotal + $tax;

                $invoice->update([
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                ]);
            }
        }
    }
}

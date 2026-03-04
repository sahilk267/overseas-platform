<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 5000);
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;
        $invoiceDate = fake()->dateTimeBetween('-1 month', 'now');
        $dueDate = fake()->dateTimeBetween($invoiceDate, '+1 month');

        return [
            'invoice_number' => 'INV-' . strtoupper(fake()->unique()->bothify('####-####')),
            'issuer_profile_id' => Profile::factory(),
            'recipient_profile_id' => Profile::factory(),
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'currency' => 'USD',
            'status' => fake()->randomElement(['draft', 'sent', 'paid', 'partially_paid', 'overdue', 'cancelled']),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}

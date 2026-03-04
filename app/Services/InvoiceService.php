<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class InvoiceService
{
    /**
     * Create an invoice from a list of line items.
     */
    public function createInvoice(int $issuerProfileId, int $recipientProfileId, array $lineItems, ?string $currency = 'INR', ?string $notes = null): Invoice
    {
        return DB::transaction(function () use ($issuerProfileId, $recipientProfileId, $lineItems, $currency, $notes) {
            $subtotal = 0;
            foreach ($lineItems as $item) {
                $subtotal += ($item['quantity'] * $item['unit_price']);
            }

            // Simple tax calculation (e.g., 18% GST)
            $taxRate = 0.18;
            $tax = $subtotal * $taxRate;
            $total = $subtotal + $tax;

            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'issuer_profile_id' => $issuerProfileId,
                'recipient_profile_id' => $recipientProfileId,
                'invoice_date' => now(),
                'due_date' => now()->addDays(15), // Default 15 days due date
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'currency' => $currency,
                'status' => 'pending',
                'notes' => $notes,
            ]);

            foreach ($lineItems as $item) {
                InvoiceLineItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            return $invoice;
        });
    }

    /**
     * Generate a unique invoice number.
     */
    protected function generateInvoiceNumber(): string
    {
        return 'INV-' . strtoupper(uniqid());
    }

    /**
     * Update invoice status.
     */
    public function updateStatus(Invoice $invoice, string $newStatus): bool
    {
        return $invoice->update(['status' => $newStatus]);
    }
}

<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentService
{
    /**
     * Process a new payment with idempotency protection.
     */
    public function processPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            // Idempotency check
            if (isset($data['idempotency_key'])) {
                $existing = Payment::where('idempotency_key', $data['idempotency_key'])->first();
                if ($existing) {
                    return $existing;
                }
            }

            // Calculation and validation logic can be added here
            $payment = Payment::create($data);

            return $payment;
        });
    }

    /**
     * Issue a refund for a payment.
     * Prevents total refunds from exceeding the original payment amount.
     */
    public function issueRefund(int $paymentId, float $amount, int $requestedById, string $reason): Refund
    {
        return DB::transaction(function () use ($paymentId, $amount, $requestedById, $reason) {
            $payment = Payment::where('id', $paymentId)
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                throw new Exception("Payment not found");
            }

            if ($payment->status !== 'completed') {
                throw new Exception("Only completed payments can be refunded");
            }

            // Calculate already refunded amount
            $totalRefunded = $payment->refunds()
                ->where('status', '!=', 'rejected')
                ->sum('amount');

            if (($totalRefunded + $amount) > $payment->amount) {
                throw new Exception("Total refund amount cannot exceed the original payment amount. (Available: " . ($payment->amount - $totalRefunded) . ")");
            }

            $refund = Refund::create([
                'payment_id' => $paymentId,
                'requested_by' => $requestedById,
                'amount' => $amount,
                'currency' => $payment->currency,
                'reason' => $reason,
                'status' => 'pending',
            ]);

            return $refund;
        });
    }

    /**
     * Finalize refund (actually move funds in a real scenario).
     */
    public function finalizeRefund(Refund $refund, string $status, ?string $transactionId = null): bool
    {
        return $refund->update([
            'status' => $status,
            'transaction_id' => $transactionId,
            'processed_at' => ($status === 'completed') ? now() : null,
        ]);
    }
}

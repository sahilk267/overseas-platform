<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Payment\ProcessPaymentRequest;
use App\Http\Requests\Api\Payment\RefundRequest;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * List payments for the active profile (sent or received).
     */
    public function index(Request $request): JsonResponse
    {
        $profileId = session('current_profile_id');

        $payments = Payment::where(function ($query) use ($profileId) {
            $query->where('payer_profile_id', $profileId)
                ->orWhere('recipient_profile_id', $profileId);
        })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($payments);
    }

    /**
     * Process a new payment.
     */
    public function store(ProcessPaymentRequest $request): JsonResponse
    {
        try {
            // Ensure the payer is the current profile (simple check)
            if ($request->payer_profile_id !== (int)session('current_profile_id')) {
                return response()->json(['message' => 'Cannot pay from another profile'], 403);
            }

            $payment = $this->paymentService->processPayment($request->validated());

            return response()->json([
                'message' => 'Payment processed successfully',
                'payment' => $payment,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment failed',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get payment details.
     */
    public function show(Payment $payment): JsonResponse
    {
        $profileId = (int)session('current_profile_id');

        if ($payment->payer_profile_id !== $profileId && $payment->recipient_profile_id !== $profileId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($payment->load(['payerProfile', 'recipientProfile', 'refunds']));
    }

    /**
     * Request a refund.
     */
    public function refund(RefundRequest $request, Payment $payment): JsonResponse
    {
        $profileId = (int)session('current_profile_id');

        // Usually the payer requests a refund
        if ($payment->payer_profile_id !== $profileId) {
            return response()->json(['message' => 'Only the payer can request a refund'], 403);
        }

        try {
            $refund = $this->paymentService->issueRefund(
                $payment->id,
                $request->amount,
                $profileId,
                $request->reason
            );

            return response()->json([
                'message' => 'Refund request created successfully',
                'refund' => $refund,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Refund request failed',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}

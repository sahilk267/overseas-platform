<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * List invoices for the active profile (issued or received).
     */
    public function index(Request $request): JsonResponse
    {
        $profileId = session('current_profile_id');

        $invoices = Invoice::where(function ($query) use ($profileId) {
            $query->where('issuer_profile_id', $profileId)
                ->orWhere('recipient_profile_id', $profileId);
        })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($invoices);
    }

    /**
     * Create a new invoice.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'recipient_profile_id' => 'required|exists:profiles,id',
            'line_items' => 'required|array|min:1',
            'line_items.*.description' => 'required|string',
            'line_items.*.quantity' => 'required|integer|min:1',
            'line_items.*.unit_price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'notes' => 'nullable|string',
        ]);

        try {
            $profileId = (int)session('current_profile_id');

            $invoice = $this->invoiceService->createInvoice(
                $profileId,
                $request->recipient_profile_id,
                $request->line_items,
                $request->currency ?? 'INR',
                $request->notes
            );

            return response()->json([
                'message' => 'Invoice created successfully',
                'invoice' => $invoice->load('lineItems'),
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create invoice',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get invoice details.
     */
    public function show(Invoice $invoice): JsonResponse
    {
        $profileId = (int)session('current_profile_id');

        if ($invoice->issuer_profile_id !== $profileId && $invoice->recipient_profile_id !== $profileId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($invoice->load(['lineItems', 'issuerProfile', 'recipientProfile']));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Execution\BookInventoryRequest;
use App\Models\AdExecution;
use App\Services\ExecutionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExecutionController extends Controller
{
    protected $executionService;

    public function __construct(ExecutionService $executionService)
    {
        $this->executionService = $executionService;
    }

    /**
     * Book inventory for a campaign.
     */
    public function book(BookInventoryRequest $request): JsonResponse
    {
        try {
            $execution = $this->executionService->bookInventory(
                $request->campaign_id,
                $request->inventory_id,
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date),
                $request->only(['notes', 'idempotency_key'])
            );

            return response()->json([
                'message' => 'Inventory booked successfully',
                'execution' => $execution,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Booking failed',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get execution details.
     */
    public function show(AdExecution $execution): JsonResponse
    {
        // Simple authorization: user must own the campaign or be the vendor
        $profileId = (int)session('current_profile_id');

        if ($execution->campaign->profile_id !== $profileId && $execution->inventory->vendor_profile_id !== $profileId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($execution->load(['campaign', 'inventory', 'proofs']));
    }

    /**
     * Update execution status.
     */
    public function updateStatus(Request $request, AdExecution $execution): JsonResponse
    {
        $profileId = (int)session('current_profile_id');

        // Only vendor or admin can update execution status directly in many flows
        if ($execution->inventory->vendor_profile_id !== $profileId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|string|in:booked,pending,completed,cancelled',
        ]);

        try {
            $this->executionService->updateStatus($execution, $request->status);

            return response()->json([
                'message' => 'Status updated successfully',
                'execution' => $execution,
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Update failed',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}

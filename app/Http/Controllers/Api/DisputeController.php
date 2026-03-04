<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Services\DisputeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    protected $disputeService;

    public function __construct(DisputeService $disputeService)
    {
        $this->disputeService = $disputeService;
    }

    /**
     * List disputes for the active profile.
     */
    public function index(Request $request): JsonResponse
    {
        $profileId = session('current_profile_id');

        $disputes = Dispute::where(function ($query) use ($profileId) {
            $query->where('complainant_profile_id', $profileId)
                ->orWhere('respondent_profile_id', $profileId);
        })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($disputes);
    }

    /**
     * Open a new dispute.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'respondent_profile_id' => 'required|exists:profiles,id',
            'related_type' => 'required|string',
            'related_id' => 'required|integer',
            'dispute_type' => 'required|string',
            'description' => 'required|string',
            'disputed_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
        ]);

        try {
            $profileId = (int)session('current_profile_id');
            $data = $request->all();
            $data['complainant_profile_id'] = $profileId;

            $dispute = $this->disputeService->openDispute($data);

            return response()->json([
                'message' => 'Dispute opened successfully',
                'dispute' => $dispute,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to open dispute',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Add a message to a dispute.
     */
    public function addMessage(Request $request, Dispute $dispute): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $profileId = (int)session('current_profile_id');

        // Check if sender is complainant or respondent (or admin check can be added)
        if ($dispute->complainant_profile_id !== $profileId && $dispute->respondent_profile_id !== $profileId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $message = $this->disputeService->addMessage(
                $dispute->id,
                $profileId,
                $request->message,
                $request->is_internal ?? false
            );

            return response()->json([
                'message' => 'Message added successfully',
                'dispute_message' => $message,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add message',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Show dispute details.
     */
    public function show(Dispute $dispute): JsonResponse
    {
        $profileId = (int)session('current_profile_id');

        if ($dispute->complainant_profile_id !== $profileId && $dispute->respondent_profile_id !== $profileId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($dispute->load(['messages', 'complainantProfile', 'respondentProfile', 'escalations']));
    }
}

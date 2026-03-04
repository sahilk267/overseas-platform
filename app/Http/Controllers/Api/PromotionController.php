<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Services\PromotionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    /**
     * List promotions for the active profile.
     */
    public function index(Request $request): JsonResponse
    {
        $profileId = session('current_profile_id');

        $promotions = Promotion::where('profile_id', $profileId)
            ->withCount('assignments')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($promotions);
    }

    /**
     * Create a new promotion.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'promotion_type' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        try {
            $profileId = (int)session('current_profile_id');
            $data = $request->all();
            $data['profile_id'] = $profileId;
            $data['status'] = 'active';

            $promotion = Promotion::create($data);

            return response()->json([
                'message' => 'Promotion created successfully',
                'promotion' => $promotion,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create promotion',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Assign promotion to a target.
     */
    public function assign(Request $request, Promotion $promotion): JsonResponse
    {
        $request->validate([
            'target_type' => 'required|string',
            'target_id' => 'required|integer',
            'cost' => 'required|numeric|min:0',
        ]);

        // Authorization check
        if ($promotion->profile_id !== (int)session('current_profile_id')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $assignment = $this->promotionService->assignPromotion(
                $promotion->id,
                $request->target_type,
                $request->target_id,
                $request->cost
            );

            return response()->json([
                'message' => 'Promotion assigned successfully',
                'assignment' => $assignment,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Assignment failed',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}

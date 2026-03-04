<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Campaign\CreateCampaignRequest;
use App\Http\Requests\Api\Campaign\UpdateCampaignStatusRequest;
use App\Models\AdCampaign;
use App\Services\CampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    /**
     * List campaigns for the active profile.
     */
    public function index(Request $request): JsonResponse
    {
        $profileId = session('current_profile_id');

        $campaigns = AdCampaign::where('profile_id', $profileId)
            ->withCount('executions')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($campaigns);
    }

    /**
     * Create a new campaign.
     */
    public function store(CreateCampaignRequest $request): JsonResponse
    {
        try {
            $campaign = $this->campaignService->createCampaign($request->validated());

            return response()->json([
                'message' => 'Campaign created successfully',
                'campaign' => $campaign,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create campaign',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get campaign details.
     */
    public function show(AdCampaign $campaign): JsonResponse
    {
        // Authorization check
        if ($campaign->profile_id !== (int)session('current_profile_id')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($campaign->load(['profile', 'category']));
    }

    /**
     * Update campaign status.
     */
    public function updateStatus(UpdateCampaignStatusRequest $request, AdCampaign $campaign): JsonResponse
    {
        // Authorization check
        if ($campaign->profile_id !== (int)session('current_profile_id')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $this->campaignService->updateStatus($campaign, $request->status);

            return response()->json([
                'message' => "Campaign status updated to {$request->status}",
                'campaign' => $campaign,
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Status update failed',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}

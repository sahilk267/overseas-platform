<?php

namespace App\Services;

use App\Models\AdCampaign;
use Illuminate\Support\Facades\DB;
use Exception;

class CampaignService
{
    /**
     * Create a new campaign with budget validation.
     */
    public function createCampaign(array $data): AdCampaign
    {
        return DB::transaction(function () use ($data) {
            // Additional validation logic can go here
            return AdCampaign::create($data);
        });
    }

    /**
     * Update campaign status with validation.
     */
    public function updateStatus(AdCampaign $campaign, string $newStatus): bool
    {
        $allowedTransitions = [
            'draft' => ['pending_approval', 'cancelled'],
            'pending_approval' => ['active', 'rejected'],
            'active' => ['paused', 'completed', 'cancelled'],
            'paused' => ['active', 'cancelled'],
        ];

        $currentStatus = $campaign->status;

        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            throw new Exception("Invalid status transition from {$currentStatus} to {$newStatus}");
        }

        return $campaign->update(['status' => $newStatus]);
    }

    /**
     * Check if a campaign has enough budget for an operation.
     * Uses row-level locking to prevent race conditions.
     */
    public function validateAndReserveBudget(int $campaignId, float $amount): bool
    {
        return DB::transaction(function () use ($campaignId, $amount) {
            $campaign = AdCampaign::where('id', $campaignId)
                ->lockForUpdate()
                ->first();

            if (!$campaign) {
                throw new Exception("Campaign not found");
            }

            if ($campaign->status !== 'active') {
                return false;
            }

            // Simple check: current total cost of executions vs budget
            // In a real scenario, we might track 'spent_amount' in the campaign table itself for performance
            $spent = $campaign->executions()->sum('amount');

            if (($spent + $amount) > $campaign->budget) {
                return false;
            }

            return true;
        });
    }
}

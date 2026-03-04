<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\PromotionAssignment;
use Illuminate\Support\Facades\DB;
use Exception;

class PromotionService
{
    /**
     * Assign a promotion to a target (model).
     * Enforces the promotion budget rule: SUM(cost) <= promotion.budget.
     */
    public function assignPromotion(int $promotionId, string $targetType, int $targetId, float $cost): PromotionAssignment
    {
        return DB::transaction(function () use ($promotionId, $targetType, $targetId, $cost) {
            // Lock the promotion record to prevent concurrent assignments
            $promotion = Promotion::where('id', $promotionId)
                ->lockForUpdate()
                ->first();

            if (!$promotion) {
                throw new Exception("Promotion not found");
            }

            if ($promotion->status !== 'active') {
                throw new Exception("Promotion is not active");
            }

            // Enforce budget rule
            $spent = $promotion->assignments()->sum('cost');

            if (($spent + $cost) > $promotion->budget) {
                throw new Exception("Promotion budget exceeded. (Available: " . ($promotion->budget - $spent) . ")");
            }

            $assignment = PromotionAssignment::create([
                'promotion_id' => $promotionId,
                'target_type' => $targetType,
                'target_id' => $targetId,
                'cost' => $cost,
                'currency' => $promotion->currency,
                'status' => 'active',
                'assigned_at' => now(),
            ]);

            return $assignment;
        });
    }

    /**
     * Update promotion status.
     */
    public function updatePromotionStatus(Promotion $promotion, string $newStatus): bool
    {
        return $promotion->update(['status' => $newStatus]);
    }
}

<?php

namespace App\Services;

use App\Models\AdExecution;
use App\Models\AdInventory;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class ExecutionService
{
    /**
     * Book an inventory slot for a campaign.
     * Prevents overlapping bookings using row-level locking on inventory.
     */
    public function bookInventory(int $campaignId, int $inventoryId, Carbon $startDate, Carbon $endDate, array $additionalData = []): AdExecution
    {
        return DB::transaction(function () use ($campaignId, $inventoryId, $startDate, $endDate, $additionalData) {
            // Lock the inventory to prevent concurrent booking attempts for the same item
            $inventory = AdInventory::where('id', $inventoryId)
                ->lockForUpdate()
                ->first();

            if (!$inventory) {
                throw new Exception("Inventory not found");
            }

            if ($inventory->status !== 'active') {
                throw new Exception("Inventory is not active");
            }

            // Check for overlapping bookings
            $overlap = AdExecution::where('inventory_id', $inventoryId)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('execution_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('execution_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                }
                );
            }
            )
                ->exists();

            if ($overlap) {
                throw new Exception("Inventory is already booked for these dates");
            }

            // Calculate cost based on inventory price and date range
            $days = $startDate->diffInDays($endDate) + 1;

            if ($days < $inventory->min_booking_days) {
                throw new Exception("Minimum booking days for this inventory is {$inventory->min_booking_days}");
            }

            $cost = $days * $inventory->price_per_day;

            $execution = AdExecution::create(array_merge($additionalData, [
                'campaign_id' => $campaignId,
                'inventory_id' => $inventoryId,
                'execution_date' => $startDate,
                'end_date' => $endDate,
                'cost' => $cost,
                'currency' => $inventory->currency,
                'status' => $inventory->requires_approval ? 'pending' : 'booked',
            ]));

            return $execution;
        });
    }

    /**
     * Update execution status with basic tracking.
     */
    public function updateStatus(AdExecution $execution, string $newStatus): bool
    {
        // Add business logic for status transitions here if needed
        return $execution->update(['status' => $newStatus]);
    }
}

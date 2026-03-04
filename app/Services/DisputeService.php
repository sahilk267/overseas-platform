<?php

namespace App\Services;

use App\Models\Dispute;
use App\Models\DisputeMessage;
use App\Models\Escalation;
use Illuminate\Support\Facades\DB;
use Exception;

class DisputeService
{
    /**
     * Open a new dispute.
     */
    public function openDispute(array $data): Dispute
    {
        return DB::transaction(function () use ($data) {
            $dispute = Dispute::create(array_merge($data, [
                'status' => 'open',
            ]));

            return $dispute;
        });
    }

    /**
     * Resolve a dispute.
     */
    public function resolve(Dispute $dispute, string $resolution, string $notes, int $resolvedByUserId): bool
    {
        return $dispute->update([
            'status' => 'resolved',
            'resolution' => $resolution,
            'resolution_notes' => $notes,
            'resolved_by' => $resolvedByUserId,
            'resolved_at' => now(),
        ]);
    }

    /**
     * Escalate a dispute to admin.
     */
    public function escalate(Dispute $dispute, int $requestedById, string $reason): Escalation
    {
        return DB::transaction(function () use ($dispute, $requestedById, $reason) {
            $dispute->update(['status' => 'escalated']);

            return Escalation::create([
                'dispute_id' => $dispute->id,
                'requested_by' => $requestedById,
                'reason' => $reason,
                'status' => 'pending',
                'priority' => 'high',
            ]);
        });
    }

    /**
     * Add a message to a dispute.
     */
    public function addMessage(int $disputeId, int $senderProfileId, string $message, bool $isInternal = false): DisputeMessage
    {
        return DisputeMessage::create([
            'dispute_id' => $disputeId,
            'sender_profile_id' => $senderProfileId,
            'message' => $message,
            'is_internal' => $isInternal,
        ]);
    }
}

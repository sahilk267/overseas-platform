<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class AppointmentService
{
    /**
     * Schedule an appointment.
     * Prevents overlapping appointments for the same provider.
     */
    public function schedule(int $requesterId, int $providerId, Carbon $scheduledAt, Carbon $endAt, array $data = []): Appointment
    {
        return DB::transaction(function () use ($requesterId, $providerId, $scheduledAt, $endAt, $data) {
            // Check for overlaps for the provider
            $overlap = Appointment::where('provider_profile_id', $providerId)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($scheduledAt, $endAt) {
                $query->whereBetween('scheduled_at', [$scheduledAt, $endAt])
                    ->orWhereBetween('end_at', [$scheduledAt, $endAt])
                    ->orWhere(function ($q) use ($scheduledAt, $endAt) {
                    $q->where('scheduled_at', '<=', $scheduledAt)
                        ->where('end_at', '>=', $endAt);
                }
                );
            }
            )
                ->exists();

            if ($overlap) {
                throw new Exception("Provider already has an appointment during this time.");
            }

            return Appointment::create(array_merge($data, [
                'requester_profile_id' => $requesterId,
                'provider_profile_id' => $providerId,
                'scheduled_at' => $scheduledAt,
                'end_at' => $endAt,
                'status' => 'pending',
            ]));
        });
    }

    /**
     * Confirm an appointment.
     */
    public function confirm(Appointment $appointment): bool
    {
        return $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Appointment $appointment, string $reason): bool
    {
        return $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }
}

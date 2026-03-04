<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * List appointments for the active profile (requested or received).
     */
    public function index(Request $request): JsonResponse
    {
        $profileId = session('current_profile_id');

        $appointments = Appointment::where(function ($query) use ($profileId) {
            $query->where('requester_profile_id', $profileId)
                ->orWhere('provider_profile_id', $profileId);
        })
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);

        return response()->json($appointments);
    }

    /**
     * Book an appointment.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'provider_profile_id' => 'required|exists:profiles,id',
            'scheduled_at' => 'required|date|after:now',
            'end_at' => 'required|date|after:scheduled_at',
            'location_id' => 'nullable|exists:locations,id',
            'meeting_type' => 'required|string|in:online,in_person',
            'meeting_url' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        try {
            $profileId = (int)session('current_profile_id');

            $appointment = $this->appointmentService->schedule(
                $profileId,
                $request->provider_profile_id,
                Carbon::parse($request->scheduled_at),
                Carbon::parse($request->end_at),
                $request->only(['location_id', 'meeting_type', 'meeting_url', 'notes'])
            );

            return response()->json([
                'message' => 'Appointment requested successfully',
                'appointment' => $appointment,
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
     * Update appointment status (confirm/cancel).
     */
    public function updateStatus(Request $request, Appointment $appointment): JsonResponse
    {
        $profileId = (int)session('current_profile_id');
        $request->validate([
            'status' => 'required|string|in:confirmed,cancelled',
            'reason' => 'required_if:status,cancelled|string|max:255',
        ]);

        try {
            if ($request->status === 'confirmed') {
                // Only provider can confirm
                if ($appointment->provider_profile_id !== $profileId) {
                    return response()->json(['message' => 'Only the provider can confirm appointments'], 403);
                }
                $this->appointmentService->confirm($appointment);
            }
            else {
                // Both can cancel
                if ($appointment->provider_profile_id !== $profileId && $appointment->requester_profile_id !== $profileId) {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }
                $this->appointmentService->cancel($appointment, $request->reason ?? 'Cancelled by user');
            }

            return response()->json([
                'message' => "Appointment {$request->status} successfully",
                'appointment' => $appointment,
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

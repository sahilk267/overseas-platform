<?php

namespace App\Http\Controllers;

use App\Models\LeadNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyLeadController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->activeProfile;

        if (!$profile || $profile->profile_type !== 'vendor') {
            return redirect()->route('dashboard');
        }

        $leads = LeadNotification::where('vendor_profile_id', $profile->id)
            ->with(['campaign.advertiser', 'campaign.category'])
            ->latest()
            ->get();

        return view('agency.leads.index', compact('leads'));
    }

    public function accept(LeadNotification $notification)
    {
        $notification->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        $notification->campaign->update([
            'status' => 'active',
            'progress_percentage' => 10,
            'last_status_update' => 'Agency accepted the task. Starting soon.',
        ]);

        return back()->with('success', 'Lead accepted! You can now manage this campaign.');
    }

    public function pass(LeadNotification $notification)
    {
        $notification->update([
            'status' => 'passed',
            'responded_at' => now(),
        ]);

        // Trigger next agency routing (Phase 10)
        // Todo: $routingService->routeToNextAgency($notification->campaign);

        return back()->with('info', 'Lead passed. It will be routed to the next available agency.');
    }
}

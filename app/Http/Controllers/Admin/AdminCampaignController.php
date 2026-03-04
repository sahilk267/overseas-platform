<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'profile.active']);
    }

    public function index(Request $request)
    {
        $profile = $request->get('current_profile');
        if (!in_array($profile->profile_type, ['admin', 'global_admin', 'developer'])) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $campaigns = AdCampaign::with('advertiserProfile', 'leadNotifications')->latest()->paginate(15);
        $vendors = \App\Models\Profile::where('profile_type', 'vendor')->where('status', 'active')->orderBy('display_name')->get();
        return view('admin.campaigns.index', compact('campaigns', 'vendors'));
    }

    public function approve(Request $request, AdCampaign $campaign)
    {
        $profile = $request->get('current_profile');
        if (!in_array($profile->profile_type, ['admin', 'global_admin', 'developer'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $campaign->update([
            'status' => 'approved',
            'approved_by' => $profile->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Campaign approved successfully.');
    }

    public function reject(Request $request, AdCampaign $campaign)
    {
        $profile = $request->get('current_profile');
        if (!in_array($profile->profile_type, ['admin', 'global_admin', 'developer'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $campaign->update([
            'status' => 'cancelled', // Or rejected if we add that to enum
            'description' => $campaign->description . "\n\nRejection Reason: " . $request->reason,
        ]);

        return back()->with('success', 'Campaign rejected.');
    }

    public function allocate(Request $request, AdCampaign $campaign)
    {
        $profile = $request->get('current_profile');
        if (!in_array($profile->profile_type, ['admin', 'global_admin', 'developer'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'vendor_profile_id' => 'required|exists:profiles,id',
        ]);

        $vendor = \App\Models\Profile::where('profile_type', 'vendor')->findOrFail($request->vendor_profile_id);

        // Update or create lead notification as accepted
        \App\Models\LeadNotification::updateOrCreate(
            ['campaign_id' => $campaign->id, 'vendor_profile_id' => $vendor->id],
            ['status' => 'accepted', 'responded_at' => now()]
        );

        // Update campaign status
        $campaign->update([
            'status' => 'active',
            'progress_percentage' => 10,
            'last_status_update' => 'Admin manually allocated this campaign to ' . $vendor->display_name,
        ]);

        return back()->with('success', 'Campaign manually allocated to ' . $vendor->display_name);
    }
}

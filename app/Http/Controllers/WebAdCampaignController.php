<?php

namespace App\Http\Controllers;

use App\Models\AdCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAdCampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'profile.active']);
    }

    public function index(Request $request)
    {
        $profile = $request->get('current_profile');
        $campaigns = AdCampaign::where('advertiser_profile_id', $profile->id)->latest()->paginate(10);
        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $categories = [];
        try {
            $categories = \App\Models\AdCategory::with('children')
                ->whereNull('parent_id')
                ->get();
        } catch (\Exception $e) {
            // Table might not exist yet if migrations haven't run
        }

        return view('campaigns.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:ad_categories,id',
            'target_city' => 'required|string|max:100',
            'address_details' => 'nullable|string',
            'campaign_goal' => 'required|string',
            'budget' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'brief' => 'nullable|string',
        ]);

        $profile = $request->get('current_profile');

        try {
            $campaign = AdCampaign::create([
                'advertiser_profile_id' => $profile->id,
                'category_id' => $request->category_id,
                'target_city' => $request->target_city,
                'address_details' => $request->address_details,
                'campaign_goal' => $request->campaign_goal,
                'name' => $request->name,
                'budget' => $request->budget,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'active',
                'currency' => 'INR',
                'progress_percentage' => 0,
                'brief' => $request->brief,
            ]);

            // Trigger Lead Routing (Phase 10)
            try {
                $routingService = new \App\Services\LeadRoutingService();
                $nearestAgencies = $routingService->findNearestAgencies($campaign, 5);

                foreach ($nearestAgencies as $agency) {
                    \App\Models\LeadNotification::create([
                        'campaign_id' => $campaign->id,
                        'vendor_profile_id' => $agency->id,
                        'status' => 'pending',
                        'notified_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Lead routing failed: " . $e->getMessage());
            }

            return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully. It has been routed to the nearest qualified agency for acceptance.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating campaign: ' . $e->getMessage())->withInput();
        }
    }

    public function show(AdCampaign $campaign)
    {
        $acceptedLead = \App\Models\LeadNotification::where('campaign_id', $campaign->id)
            ->where('status', 'accepted')
            ->first();

        $agencyProfile = $acceptedLead ? $acceptedLead->vendor : null;

        return view('campaigns.show', compact('campaign', 'agencyProfile'));
    }

    public function edit(AdCampaign $campaign)
    {
        $profile = request()->get('current_profile');
        if ($campaign->advertiser_profile_id !== $profile->id) {
            return redirect()->route('campaigns.index')->with('error', 'Unauthorized access.');
        }

        $categories = \App\Models\AdCategory::with('children')->whereNull('parent_id')->get();
        return view('campaigns.create', [
            'campaign' => $campaign,
            'categories' => $categories,
            'isEdit' => true
        ]);
    }

    public function update(Request $request, AdCampaign $campaign)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:ad_categories,id',
            'target_city' => 'required|string|max:100',
            'campaign_goal' => 'required|string',
            'budget' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $profile = $request->get('current_profile');
        if ($campaign->advertiser_profile_id !== $profile->id) {
            return redirect()->route('campaigns.index')->with('error', 'Unauthorized access.');
        }

        $campaign->update($request->only([
            'name',
            'category_id',
            'target_city',
            'address_details',
            'campaign_goal',
            'budget',
            'start_date',
            'end_date',
            'brief'
        ]));

        return redirect()->route('campaigns.show', $campaign->id)->with('success', 'Campaign updated successfully.');
    }

    public function destroy(AdCampaign $campaign)
    {
        $profile = request()->get('current_profile');
        if ($campaign->advertiser_profile_id !== $profile->id) {
            return redirect()->route('campaigns.index')->with('error', 'Unauthorized.');
        }

        // Delete related lead notifications
        \App\Models\LeadNotification::where('campaign_id', $campaign->id)->delete();

        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');
    }
}

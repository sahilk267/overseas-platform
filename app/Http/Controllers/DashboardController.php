<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'profile.active']);
    }

    /**
     * Show the application dashboard based on profile type.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $profile = $request->get('current_profile');

        if (!$profile) {
            return redirect()->route('profiles.index');
        }

        $stats = $this->getStatsByProfile($profile);
        $activities = $this->getActivityByProfile($profile);

        $data = [
            'profile' => $profile,
            'user' => $user,
            'stats' => $stats,
            'activities' => $activities,
        ];

        $viewName = match ($profile->profile_type) {
            'advertiser' => 'dashboards.advertiser',
            'vendor' => 'dashboards.vendor',
            'talent' => 'dashboards.talent',
            'admin', 'global_admin', 'developer' => 'dashboards.admin',
            'event_organizer' => 'dashboards.organizer',
            default => 'dashboard',
        };

        if (!view()->exists($viewName)) {
            return view('dashboard', $data);
        }

        return view($viewName, $data);
    }

    private function getActivityByProfile($profile)
    {
        return match ($profile->profile_type) {
            'admin', 'global_admin', 'developer' => [
                'recent_users' => \App\Models\User::latest()->take(5)->get(),
                'recent_disputes' => \App\Models\Dispute::with(['complainantProfile', 'respondentProfile'])->latest()->take(5)->get(),
            ],
            'advertiser' => [
                'recent_campaigns' => \App\Models\AdCampaign::where('advertiser_profile_id', $profile->id)->latest()->take(5)->get(),
            ],
            'vendor' => [
                'pending_requests' => \App\Models\LeadNotification::where('vendor_profile_id', $profile->id)
                    ->with(['campaign.advertiserProfile', 'campaign.category'])
                    ->where('status', 'pending')
                    ->latest()
                    ->take(5)
                    ->get(),
            ],
            'talent' => [
                'upcoming_appointments' => \App\Models\Appointment::with(['requesterProfile', 'location'])
                    ->where('provider_profile_id', $profile->id)
                    ->where('scheduled_at', '>=', now())
                    ->orderBy('scheduled_at', 'asc')
                    ->take(5)
                    ->get(),
            ],
            'event_organizer' => [
                'live_events' => \App\Models\Event::where('organizer_profile_id', $profile->id)
                    ->latest()
                    ->take(5)
                    ->get(),
            ],
            default => [],
        };
    }

    private function getStatsByProfile($profile)
    {
        return match ($profile->profile_type) {
            'admin', 'global_admin', 'developer' => [
                'total_users' => \App\Models\User::count(),
                'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
                'total_clients' => \App\Models\Profile::where('profile_type', 'advertiser')->count(),
                'total_vendors' => \App\Models\Profile::where('profile_type', 'vendor')->count(),
                'pending_campaigns' => \App\Models\AdCampaign::whereIn('status', ['pending_approval', 'active'])->count(),
                'pending_verifications' => \App\Models\UserVerification::where('status', 'pending')->count(),
            ],
            'advertiser' => [
                'active_campaigns' => \App\Models\AdCampaign::where('advertiser_profile_id', $profile->id)->where('status', 'active')->count(),
                'total_spend_mtd' => \App\Models\Payment::where('payer_profile_id', $profile->id)->where('status', 'completed')->whereMonth('created_at', now()->month)->sum('amount'),
                'pending_approvals' => \App\Models\AdCampaign::where('advertiser_profile_id', $profile->id)->where('status', 'pending')->count(),
                'running_executions' => \App\Models\AdExecution::whereHas('campaign', fn($q) => $q->where('advertiser_profile_id', $profile->id))->where('status', 'active')->count(),
            ],
            'vendor' => [
                'total_inventory' => \App\Models\AdInventory::where('vendor_profile_id', $profile->id)->count(),
                'active_bookings' => \App\Models\AdExecution::whereHas('inventory', fn($q) => $q->where('vendor_profile_id', $profile->id))->where('status', 'active')->count(),
                'total_earnings' => \App\Models\Payment::where('recipient_profile_id', $profile->id)->where('status', 'completed')->sum('amount'),
                'pending_requests' => \App\Models\LeadNotification::where('vendor_profile_id', $profile->id)->where('status', 'pending')->count(),
            ],
            'talent' => [
                'rating' => $profile->rating,
                'appointments' => \App\Models\Appointment::where('provider_profile_id', $profile->id)->count(),
                'pending_payouts' => \App\Models\Payment::where('recipient_profile_id', $profile->id)->where('status', 'pending')->sum('amount'),
                'profile_views' => 0,
            ],
            'event_organizer' => [
                'active_events' => \App\Models\Event::where('organizer_profile_id', $profile->id)->where('status', 'active')->count(),
                'pending_talent' => \App\Models\Appointment::where('requester_profile_id', $profile->id)->where('status', 'pending')->count(),
                'total_budget' => \App\Models\Event::where('organizer_profile_id', $profile->id)->sum('budget'),
                'upcoming_deadlines' => 0,
            ],
            default => [],
        };
    }
}

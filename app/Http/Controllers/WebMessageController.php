<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Profile;
use App\Models\LeadNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WebMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'profile.active']);
    }

    public function index(Request $request)
    {
        $currentProfile = $request->get('current_profile');

        // Get uniquely grouped conversations with the latest message for each
        $conversations = Message::where(function ($query) use ($currentProfile) {
            $query->where('sender_profile_id', $currentProfile->id)
                ->orWhere('receiver_profile_id', $currentProfile->id);
        })
            ->with(['senderProfile', 'receiverProfile'])
            ->latest()
            ->get()
            ->unique(function ($message) use ($currentProfile) {
                // Group by the other participant's ID
                return $message->sender_profile_id == $currentProfile->id
                    ? $message->receiver_profile_id
                    : $message->sender_profile_id;
            });

        return view('messages.index', compact('conversations', 'currentProfile'));
    }

    public function show(Request $request, Profile $profile)
    {
        $currentProfile = $request->get('current_profile');

        // Check if allowed to talk
        if (!$this->canMessage($currentProfile, $profile)) {
            return redirect()->route('messages.index')->with('error', 'You are not authorized to message this profile.');
        }

        $messages = Message::where(function ($query) use ($currentProfile, $profile) {
            $query->where('sender_profile_id', $currentProfile->id)
                ->where('receiver_profile_id', $profile->id);
        })
            ->orWhere(function ($query) use ($currentProfile, $profile) {
                $query->where('sender_profile_id', $profile->id)
                    ->where('receiver_profile_id', $currentProfile->id);
            })
            ->oldest()
            ->get();

        // Mark as read
        Message::where('receiver_profile_id', $currentProfile->id)
            ->where('sender_profile_id', $profile->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('messages.show', compact('messages', 'profile', 'currentProfile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:profiles,id',
            'body' => 'required|string',
        ]);

        $currentProfile = $request->get('current_profile');
        $receiver = Profile::findOrFail($request->receiver_id);

        if (!$this->canMessage($currentProfile, $receiver)) {
            return back()->with('error', 'Unauthorized message attempt.');
        }

        Message::create([
            'sender_profile_id' => $currentProfile->id,
            'receiver_profile_id' => $receiver->id,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Message sent.');
    }

    /**
     * Determine if $sender can message $receiver
     */
    private function canMessage(Profile $sender, Profile $receiver)
    {
        // 1. If receiver is Admin, anyone can message
        if (in_array($receiver->profile_type, ['admin', 'global_admin', 'developer'])) {
            return true;
        }

        // 2. If sender is Admin, can message anyone
        if (in_array($sender->profile_type, ['admin', 'global_admin', 'developer'])) {
            return true;
        }

        // 3. Sender is Advertiser, Receiver is Vendor
        if ($sender->profile_type === 'advertiser' && $receiver->profile_type === 'vendor') {
            return LeadNotification::where('vendor_profile_id', $receiver->id)
                ->where('status', 'accepted')
                ->whereHas('campaign', function ($q) use ($sender) {
                    $q->where('advertiser_profile_id', $sender->id);
                })->exists();
        }

        // 4. Sender is Vendor, Receiver is Advertiser
        if ($sender->profile_type === 'vendor' && $receiver->profile_type === 'advertiser') {
            return LeadNotification::where('vendor_profile_id', $sender->id)
                ->where('status', 'accepted')
                ->whereHas('campaign', function ($q) use ($receiver) {
                    $q->where('advertiser_profile_id', $receiver->id);
                })->exists();
        }

        return false;
    }
}

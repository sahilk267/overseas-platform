<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class WebEventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'profile.active']);
    }

    public function index(Request $request)
    {
        $profile = $request->get('current_profile');
        $events = Event::where('organizer_profile_id', $profile->id)->latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('events.index')->with('success', 'Event created successfully (Skeleton).');
    }
}

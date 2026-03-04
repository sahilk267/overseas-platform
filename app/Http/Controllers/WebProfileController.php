<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebProfileController extends Controller
{
    public function index(Request $request)
    {
        $profiles = $request->user()->profiles()->where('status', 'active')->get();

        // If no profiles exist, redirect to creation flow
        if ($profiles->isEmpty()) {
            return redirect()->route('profiles.create');
        }

        return view('profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('profiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'profile_type' => 'required|in:advertiser,vendor,talent,event_organizer',
            'display_name' => 'required|string|max:255',
        ]);

        $profile = $request->user()->profiles()->create([
            'profile_type' => $request->profile_type,
            'display_name' => $request->display_name,
            'status' => 'active', // For demo/initial phase
        ]);

        session([
            'current_profile_id' => $profile->id,
            'current_profile_type' => $profile->profile_type,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profile created successfully!');
    }

    public function edit(Request $request, \App\Models\Profile $profile)
    {
        // Ensure the profile belongs to the user
        if ($profile->user_id !== $request->user()->id) {
            abort(403);
        }

        $categories = \App\Models\AdCategory::with('children')->whereNull('parent_id')->get();
        $selectedCategories = $profile->categories->pluck('id')->toArray();
        $locations = \App\Models\Location::orderBy('city')->get();

        return view('profiles.edit', compact('profile', 'categories', 'selectedCategories', 'locations'));
    }

    public function update(Request $request, \App\Models\Profile $profile)
    {
        // Ensure the profile belongs to the user
        if ($profile->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'display_name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:255',
            'location_id' => 'nullable|exists:locations,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:ad_categories,id',
        ]);

        $profile->update([
            'display_name' => $request->display_name,
            'bio' => $request->bio,
            'city' => $request->city,
            'location_id' => $request->location_id,
        ]);

        if ($request->has('categories')) {
            $profile->categories()->sync($request->categories);
        } else {
            $profile->categories()->detach();
        }

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }

    public function switchProfile(Request $request)
    {
        $request->validate([
            'profile_id' => 'required|exists:profiles,id',
        ]);

        $profile = $request->user()->profiles()->where('status', 'active')->findOrFail($request->profile_id);

        session([
            'current_profile_id' => $profile->id,
            'current_profile_type' => $profile->profile_type,
        ]);

        return redirect()->route('dashboard')->with('success', 'Switched to ' . ucfirst($profile->profile_type));
    }
}

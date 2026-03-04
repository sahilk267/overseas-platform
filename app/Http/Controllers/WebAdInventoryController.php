<?php

namespace App\Http\Controllers;

use App\Models\AdInventory;
use Illuminate\Http\Request;

class WebAdInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'profile.active']);
    }

    public function index(Request $request)
    {
        $profile = $request->get('current_profile');
        $inventory = AdInventory::with('location')->where('vendor_profile_id', $profile->id)->latest()->paginate(10);
        return view('inventory.index', compact('inventory'));
    }

    public function create()
    {
        $categories = \App\Models\AdCategory::whereNull('parent_id')->with('children')->get();
        $locations = \App\Models\Location::orderBy('city')->get();
        return view('inventory.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $profile = $request->get('current_profile');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:ad_categories,id',
            'location_id' => 'required|exists:locations,id',
            'inventory_type' => 'required|string',
            'dimensions' => 'nullable|string|max:50',
            'price_per_day' => 'required|numeric|min:0',
            'min_booking_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $inventory = new \App\Models\AdInventory($validated);
        $inventory->vendor_profile_id = $profile->id;
        $inventory->currency = 'INR';
        $inventory->status = 'active';
        $inventory->save();

        return redirect()->route('inventory.index')->with('success', 'Asset added to your inventory successfully.');
    }
}

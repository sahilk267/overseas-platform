<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\AdCategory;
use Illuminate\Http\Request;

class AdminAgencyController extends Controller
{
    public function index()
    {
        $agencies = Profile::where('profile_type', 'vendor')
            ->with('categories')
            ->latest()
            ->paginate(15);

        return view('admin.agencies.index', compact('agencies'));
    }

    public function edit(Profile $agency)
    {
        if ($agency->profile_type !== 'vendor') {
            return redirect()->route('admin.agencies.index');
        }

        $categories = AdCategory::with('children')->whereNull('parent_id')->get();
        return view('admin.agencies.edit', compact('agency', 'categories'));
    }

    public function updateCategories(Request $request, Profile $agency)
    {
        $request->validate([
            'categories' => 'array',
            'categories.*' => 'exists:ad_categories,id',
        ]);

        $agency->categories()->sync($request->categories);

        return redirect()->route('admin.agencies.index')->with('success', 'Agency categories updated successfully!');
    }

    public function approve(Profile $agency)
    {
        $agency->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Agency approved for the platform!');
    }
}

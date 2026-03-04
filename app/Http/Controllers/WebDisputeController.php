<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use Illuminate\Http\Request;

class WebDisputeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'profile.active']);
    }

    public function index(Request $request)
    {
        $profile = $request->get('current_profile');
        $disputes = Dispute::where('complainant_profile_id', $profile->id)
            ->orWhere('respondent_profile_id', $profile->id)
            ->latest()
            ->paginate(10);

        return view('disputes.index', compact('disputes'));
    }

    public function show(Dispute $dispute)
    {
        return view('disputes.show', compact('dispute'));
    }
}

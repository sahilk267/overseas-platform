<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Profile;
use App\Models\User;
use App\Models\AdCampaign;
use App\Models\AdExecution;
use App\Models\AdInventory;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Message;
use App\Models\Event;
use App\Models\Dispute;
use App\Models\Review;
use App\Models\AuditLog;
use App\Models\ActivityLog;

class AdminCleanupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'profile.active']);
    }

    /**
     * Show the cleanup confirmation page or handle the cleanup.
     */
    public function index()
    {
        return view('admin.cleanup.index');
    }

    /**
     * Perform the data cleanup.
     */
    public function cleanup(Request $request)
    {
        // Security check: Ensure user is global_admin, developer or admin
        $profile = $request->get('current_profile') ?? auth()->user()->profiles()->where('id', session('current_profile_id'))->first();

        if (!$profile || !in_array($profile->profile_type, ['admin', 'global_admin', 'developer'])) {
            return back()->with('error', 'Unauthorized action.');
        }

        if (!$request->has('confirm_cleanup')) {
            return back()->with('error', 'Please confirm you want to delete all data.');
        }

        try {
            DB::beginTransaction();

            // Disable foreign key checks for truncation
            Schema::disableForeignKeyConstraints();

            // 1. Delete Campaigns and Executions
            AdExecution::query()->forceDelete();
            AdCampaign::query()->forceDelete();

            // 2. Delete Inventory
            AdInventory::query()->forceDelete();

            // 3. Delete Communications
            Message::query()->forceDelete();
            Notification::query()->forceDelete();

            // 4. Delete Events, Disputes, Appointments
            Event::query()->forceDelete();
            Dispute::query()->forceDelete();
            Review::query()->forceDelete();
            \App\Models\Appointment::query()->forceDelete();

            // 5. Delete Payments, Invoices, Commissions
            Payment::query()->forceDelete();
            \App\Models\Invoice::query()->forceDelete();
            \App\Models\Commission::query()->forceDelete();
            \App\Models\Contract::query()->forceDelete();

            // 6. Delete Logs and Reports
            AuditLog::query()->forceDelete();
            ActivityLog::query()->forceDelete();
            \App\Models\Report::query()->forceDelete();

            // 7. Delete ALL Profiles (Keep ONLY current user's ADMIN roles)
            Profile::where('user_id', '!=', auth()->id())
                ->orWhere(function ($query) {
                    $query->where('user_id', auth()->id())
                        ->whereNotIn('profile_type', ['admin', 'global_admin', 'developer']);
                })
                ->orWhereNull('user_id')
                ->forceDelete();

            // 8. Delete ALL Users (EXCEPT current user)
            User::where('id', '!=', auth()->id())->forceDelete();

            // 9. Extra Cleanup (Optional but good)
            DB::table('category_profiles')->delete();
            \App\Models\TalentProfile::query()->forceDelete();
            \App\Models\AvailabilitySlot::query()->forceDelete();
            \App\Models\Promotion::query()->forceDelete();

            Schema::enableForeignKeyConstraints();

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'System data has been cleaned successfully. Test users and dummy content removed.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Schema::enableForeignKeyConstraints();
            \Illuminate\Support\Facades\Log::error('Cleanup failed: ' . $e->getMessage());
            return back()->with('error', 'Cleanup failed: ' . $e->getMessage());
        }
    }
}

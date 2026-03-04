<?php

namespace Tests\Feature\Commands;

use App\Models\Contract;
use App\Models\Notification;
use App\Models\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class BackgroundJobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cleanup_sessions_command_removes_expired_sessions()
    {
        // Create an expired session
        Session::create([
            'id' => 'expired_session',
            'last_activity' => Carbon::now()->subDays(2)->timestamp,
            'payload' => 'test',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test'
        ]);

        // Create a recent session
        Session::create([
            'id' => 'recent_session',
            'last_activity' => Carbon::now()->timestamp,
            'payload' => 'test',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test'
        ]);

        Artisan::call('umaep:cleanup-sessions');

        $this->assertDatabaseMissing('sessions', ['id' => 'expired_session']);
        $this->assertDatabaseHas('sessions', ['id' => 'recent_session']);
    }

    public function test_check_contracts_command_creates_notifications()
    {
        $contract = Contract::factory()->create([
            'end_date' => Carbon::now()->addDays(7)->toDateString(),
            'status' => 'signed',
        ]);

        Artisan::call('umaep:check-contracts');

        $this->assertDatabaseHas('notifications', [
            'profile_id' => $contract->party_a_profile_id,
            'title' => 'Contract Expiring Soon'
        ]);

        $this->assertDatabaseHas('notifications', [
            'profile_id' => $contract->party_b_profile_id,
            'title' => 'Contract Expiring Soon'
        ]);
    }
}

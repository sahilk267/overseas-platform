<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfilePermission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_can_check_active_permission()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $permission = Permission::firstOrCreate(['slug' => 'test-permission', 'name' => 'Test Permission']);

        // Grant permission
        $profile->grantPermission($permission->id, $user->id);

        $this->assertTrue($profile->hasPermission('test-permission'));
    }

    public function test_profile_does_not_have_revoked_permission()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $permission = Permission::firstOrCreate(['slug' => 'revoked-permission', 'name' => 'Revoked Permission']);

        // Grant and then revoke
        $profile->grantPermission($permission->id, $user->id);
        $profile->revokePermission($permission->id, $user->id);

        $this->assertFalse($profile->hasPermission('revoked-permission'));
    }

    public function test_profile_does_not_have_expired_permission()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $permission = Permission::firstOrCreate(['slug' => 'expired-permission', 'name' => 'Expired Permission']);

        // Grant with past expiry
        ProfilePermission::create([
            'profile_id' => $profile->id,
            'permission_id' => $permission->id,
            'granted_by' => $user->id,
            'granted_at' => now(),
            'expires_at' => now()->subDay(),
        ]);

        $this->assertFalse($profile->hasPermission('expired-permission'));
    }

    public function test_is_admin_check()
    {
        $adminProfile = Profile::factory()->make(['profile_type' => 'admin']);
        $talentProfile = Profile::factory()->make(['profile_type' => 'talent']);

        $this->assertTrue($adminProfile->isAdmin());
        $this->assertFalse($talentProfile->isAdmin());
    }
}

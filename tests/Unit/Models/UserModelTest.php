<?php

namespace Tests\Unit\Models;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_active_profile_check()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'status' => 'active'
        ]);

        // Simulating session behavior
        session(['current_profile_id' => $profile->id]);

        // We need to implement activeProfile() logic in User model or mock it
        // The User model has helper methods as per Phase 4 summary.
        $this->assertTrue($user->hasProfile($profile->id));
        $this->assertEquals($profile->id, $user->activeProfile()->id);
    }
}

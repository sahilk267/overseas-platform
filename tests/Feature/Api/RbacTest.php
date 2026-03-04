<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_blocks_unauthorized_profile_access()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'status' => 'active'
        ]);

        Sanctum::actingAs($user);

        // Simulating the EnsureProfileActive middleware requirement
        session(['current_profile_id' => $profile->id]);

        // We'll use a dummy route that uses the 'profile.permission' middleware
        // For testing purposes, we can register a temporary route in the test
        \Illuminate\Support\Facades\Route::get('/test-rbac', function () {
            return response()->json(['message' => 'success']);
        })->middleware(['web', 'profile.permission:manage-inventory']);

        $response = $this->getJson('/test-rbac');

        $response->assertStatus(403);
    }

    public function test_middleware_allows_authorized_profile_access()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'status' => 'active'
        ]);

        $permission = Permission::firstOrCreate(['slug' => 'manage-inventory', 'name' => 'Manage Inventory']);
        $profile->grantPermission($permission->id, $user->id);

        Sanctum::actingAs($user);
        session(['current_profile_id' => $profile->id]);

        \Illuminate\Support\Facades\Route::get('/test-rbac-success', function () {
            return response()->json(['message' => 'success']);
        })->middleware(['web', 'profile.permission:manage-inventory']);

        $response = $this->getJson('/test-rbac-success');

        $response->assertStatus(200);
    }
}

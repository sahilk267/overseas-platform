<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Permission;
use App\Models\ProfilePermission;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfilePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminProfile = Profile::where('profile_type', 'admin')->first();
        $adminUser = User::where('email', 'admin@umaep.com')->first();

        if ($adminProfile && $adminUser) {
            $permissions = Permission::all();
            foreach ($permissions as $permission) {
                ProfilePermission::firstOrCreate(
                    ['profile_id' => $adminProfile->id, 'permission_id' => $permission->id],
                    ['granted_by' => $adminUser->id, 'granted_at' => now()]
                );
            }
        }

        $advertiserProfiles = Profile::where('profile_type', 'advertiser')->get();
        $campaignPermissions = Permission::whereIn('slug', [
            'campaign.create',
            'campaign.edit',
            'campaign.view',
            'payment.process',
            'payment.view',
        ])->get();

        foreach ($advertiserProfiles as $profile) {
            foreach ($campaignPermissions as $permission) {
                ProfilePermission::firstOrCreate(
                    ['profile_id' => $profile->id, 'permission_id' => $permission->id],
                    ['granted_by' => $adminUser?->id, 'granted_at' => now()]
                );
            }
        }

        $vendorProfiles = Profile::where('profile_type', 'vendor')->get();
        $inventoryPermissions = Permission::whereIn('slug', [
            'inventory.create',
            'inventory.edit',
            'inventory.view',
            'payment.view',
        ])->get();

        foreach ($vendorProfiles as $profile) {
            foreach ($inventoryPermissions as $permission) {
                ProfilePermission::firstOrCreate(
                    ['profile_id' => $profile->id, 'permission_id' => $permission->id],
                    ['granted_by' => $adminUser?->id, 'granted_at' => now()]
                );
            }
        }
    }
}

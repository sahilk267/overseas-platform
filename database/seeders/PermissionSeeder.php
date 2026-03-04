<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Campaign Management (using 'content' category)
            ['name' => 'Create Campaign', 'slug' => 'campaign.create', 'description' => 'Create advertising campaigns', 'category' => 'content'],
            ['name' => 'Edit Campaign', 'slug' => 'campaign.edit', 'description' => 'Edit advertising campaigns', 'category' => 'content'],
            ['name' => 'Delete Campaign', 'slug' => 'campaign.delete', 'description' => 'Delete advertising campaigns', 'category' => 'content'],
            ['name' => 'Approve Campaign', 'slug' => 'campaign.approve', 'description' => 'Approve advertising campaigns', 'category' => 'content'],
            ['name' => 'View Campaign', 'slug' => 'campaign.view', 'description' => 'View advertising campaigns', 'category' => 'content'],

            // Inventory Management (using 'content' category)
            ['name' => 'Create Inventory', 'slug' => 'inventory.create', 'description' => 'Create advertising inventory', 'category' => 'content'],
            ['name' => 'Edit Inventory', 'slug' => 'inventory.edit', 'description' => 'Edit advertising inventory', 'category' => 'content'],
            ['name' => 'Delete Inventory', 'slug' => 'inventory.delete', 'description' => 'Delete advertising inventory', 'category' => 'content'],
            ['name' => 'View Inventory', 'slug' => 'inventory.view', 'description' => 'View advertising inventory', 'category' => 'content'],

            // Payment Management (using 'financial' category)
            ['name' => 'Process Payment', 'slug' => 'payment.process', 'description' => 'Process payments', 'category' => 'financial'],
            ['name' => 'View Payment', 'slug' => 'payment.view', 'description' => 'View payments', 'category' => 'financial'],
            ['name' => 'Refund Payment', 'slug' => 'payment.refund', 'description' => 'Refund payments', 'category' => 'financial'],

            // Profile Management (using 'profile' category)
            ['name' => 'Approve Profile', 'slug' => 'profile.approve', 'description' => 'Approve user profiles', 'category' => 'profile'],
            ['name' => 'Suspend Profile', 'slug' => 'profile.suspend', 'description' => 'Suspend user profiles', 'category' => 'profile'],
            ['name' => 'View Profile', 'slug' => 'profile.view', 'description' => 'View user profiles', 'category' => 'profile'],

            // Admin (using 'system' category)
            ['name' => 'Admin Access', 'slug' => 'admin.access', 'description' => 'Full admin access', 'category' => 'system'],
            ['name' => 'View Reports', 'slug' => 'reports.view', 'description' => 'View system reports', 'category' => 'system'],
            ['name' => 'Manage Users', 'slug' => 'users.manage', 'description' => 'Manage users', 'category' => 'system'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']], // Use slug as unique identifier
                $permission
            );
        }
    }
}

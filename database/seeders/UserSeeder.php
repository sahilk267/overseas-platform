<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user
        User::firstOrCreate(
        ['email' => 'admin@umaep.com'],
        [
            'password' => Hash::make('password'),
            'phone' => '+919876543210',
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        );

        // Create Developer user
        User::firstOrCreate(
        ['email' => 'developer@umaep.com'],
        [
            'password' => Hash::make('password'),
            'phone' => '+919876543219',
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        );

        // Create Global Admin user
        User::firstOrCreate(
        ['email' => 'superadmin@umaep.com'],
        [
            'password' => Hash::make('password'),
            'phone' => '+919876543218',
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        );

        // Create test users (idempotent)
        User::firstOrCreate(
        ['email' => 'advertiser@umaep.com'],
        [
            'password' => Hash::make('password'),
            'phone' => '+919876543211',
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        );

        User::firstOrCreate(
        ['email' => 'vendor@umaep.com'],
        [
            'password' => Hash::make('password'),
            'phone' => '+919876543212',
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        );

        User::firstOrCreate(
        ['email' => 'talent@umaep.com'],
        [
            'password' => Hash::make('password'),
            'phone' => '+919876543213',
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        );

        // Create additional random users (only if not already seeded)
        $existingUserCount = User::count();
        if ($existingUserCount < 25) { // 4 test users + 20 random = 24, check for 25 to be safe
            $needed = 25 - $existingUserCount;
            User::factory($needed)->create();
        }
    }
}

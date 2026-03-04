<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $locations = Location::all();
        $users = User::all();

        // Clear existing profiles safely by disabling foreign key checks
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \Illuminate\Support\Facades\DB::table('profiles')->truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // Create all roles for the admin user for testing convenience
        $adminUser = User::where('email', 'admin@umaep.com')->first();
        if ($adminUser) {
            $roles = [
                'admin' => 'System administrator for the UMAEP platform.',
                'developer' => 'Full technical access for platform maintenance and development.',
                'advertiser' => 'Advertising agency profile.',
                'vendor' => 'Media and logistics vendor profile.',
                'talent' => 'Professional performer profile.',
                'event_organizer' => 'Event coordination profile.',
            ];

            foreach ($roles as $type => $bio) {
                Profile::updateOrCreate(
                ['user_id' => $adminUser->id, 'profile_type' => $type],
                [
                    'display_name' => 'Admin ' . ucfirst($type),
                    'status' => 'active',
                    'approved_at' => now(),
                    'location_id' => $locations->random()->id,
                    'rating' => 5.00,
                    'bio' => $bio,
                ]
                );
            }
        }

        // Special Profile for Developer User
        $devUser = User::where('email', 'developer@umaep.com')->first();
        if ($devUser) {
            Profile::updateOrCreate(
            ['user_id' => $devUser->id, 'profile_type' => 'developer'],
            [
                'display_name' => 'Core Developer',
                'status' => 'active',
                'approved_at' => now(),
                'location_id' => $locations->random()->id,
                'rating' => 5.00,
                'bio' => 'Full access developer account.',
            ]
            );
        }

        // Special Profile for SuperAdmin User
        $superUser = User::where('email', 'superadmin@umaep.com')->first();
        if ($superUser) {
            Profile::updateOrCreate(
            ['user_id' => $superUser->id, 'profile_type' => 'global_admin'],
            [
                'display_name' => 'Global Administrator',
                'status' => 'active',
                'approved_at' => now(),
                'location_id' => $locations->random()->id,
                'rating' => 5.00,
                'bio' => 'Supreme platform management access.',
            ]
            );
        }

        // Create standard test users (advertiser@, vendor@, talent@)
        $testUsers = [
            'advertiser@umaep.com' => ['type' => 'advertiser', 'name' => 'Test Advertiser', 'bio' => 'A pro advertiser account for testing.'],
            'vendor@umaep.com' => ['type' => 'vendor', 'name' => 'Test Vendor', 'bio' => 'A pro vendor account for testing.'],
            'talent@umaep.com' => ['type' => 'talent', 'name' => 'Test Talent', 'bio' => 'A pro talent account for testing.'],
        ];

        foreach ($testUsers as $email => $data) {
            $user = User::where('email', $email)->first();
            if ($user) {
                Profile::updateOrCreate(
                ['user_id' => $user->id, 'profile_type' => $data['type']],
                [
                    'display_name' => $data['name'],
                    'status' => 'active',
                    'approved_at' => now(),
                    'location_id' => $locations->random()->id,
                    'rating' => 4.5,
                    'bio' => $data['bio'],
                ]
                );
            }
        }

        // Create additional random profiles for other users with English bios
        $englishBios = [
            'Experienced marketing professional with a focus on digital growth.',
            'Media specialist providing top-tier placement services globally.',
            'Creative artist looking to connect with innovative brands.',
            'Logistics expert with over 10 years of event management experience.',
            'Independent consultant helping businesses scale their reach.',
        ];

        foreach ($users->where('email', '!=', 'admin@umaep.com')->take(10) as $user) {
            Profile::factory()
                ->for($user)
                ->for($locations->random(), 'location')
                ->approved()
                ->create([
                'bio' => $englishBios[array_rand($englishBios)],
                'display_name' => $user->name ?? fake()->name(),
            ]);
        }
    }
}

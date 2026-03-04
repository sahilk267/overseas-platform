<?php

namespace Database\Seeders;

use App\Models\UserVerification;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserVerificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $adminUser = User::where('email', 'admin@umaep.com')->first();

        foreach ($users->take(10) as $user) {
            // Email verification
            UserVerification::create([
                'user_id' => $user->id,
                'verification_type' => 'email',
                'status' => $user->email_verified_at ? 'verified' : 'pending',
                'verified_by' => $user->email_verified_at ? $adminUser?->id : null,
                'verified_at' => $user->email_verified_at,
            ]);

            // Phone verification (if phone exists)
            if ($user->phone) {
                UserVerification::create([
                    'user_id' => $user->id,
                    'verification_type' => 'phone',
                    'status' => $user->phone_verified_at ? 'verified' : 'pending',
                    'verified_by' => $user->phone_verified_at ? $adminUser?->id : null,
                    'verified_at' => $user->phone_verified_at,
                ]);
            }

            // Identity verification (random)
            if (rand(0, 1)) {
                UserVerification::create([
                    'user_id' => $user->id,
                    'verification_type' => 'identity',
                    'status' => fake()->randomElement(['pending', 'verified', 'rejected']),
                    'document_type' => fake()->randomElement(['passport', 'drivers_license', 'aadhaar']),
                    'document_path' => fake()->optional(0.5)->filePath(),
                    'verified_by' => fake()->optional(0.5)->randomElement([$adminUser?->id]),
                    'verified_at' => fake()->optional(0.5)->dateTime(),
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');

        // Core foundation (no dependencies)
        $this->call([
            LocationSeeder::class,
            PermissionSeeder::class,
        ]);

        // Users and Profiles
        $this->call([
            UserSeeder::class,
            ProfileSeeder::class,
            ProfilePermissionSeeder::class,
        ]);

        // Advertising module
        $this->call([
            AdCategorySeeder::class,
            AdInventorySeeder::class,
            AdCampaignSeeder::class,
            AdExecutionSeeder::class,
        ]);

        // Talent module
        $this->call([
            TalentProfileSeeder::class,
        ]);

        // Verification
        $this->call([
            UserVerificationSeeder::class,
        ]);

        // Financial module
        $this->call([
            PaymentMethodSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
            RefundSeeder::class,
            CommissionSeeder::class,
        ]);

        // Contracts and Promotions
        $this->call([
            ContractSeeder::class,
            PromotionSeeder::class,
        ]);

        // Reviews
        $this->call([
            ReviewSeeder::class,
        ]);

        $this->command->info('Database seeding completed!');
    }
}

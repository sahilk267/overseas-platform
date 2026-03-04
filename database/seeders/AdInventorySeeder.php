<?php

namespace Database\Seeders;

use App\Models\AdInventory;
use App\Models\Profile;
use App\Models\AdCategory;
use App\Models\Location;
use Illuminate\Database\Seeder;

class AdInventorySeeder extends Seeder
{
    public function run(): void
    {
        $vendorProfiles = Profile::where('profile_type', 'vendor')->get();
        $categories = AdCategory::all();
        $locations = Location::all();

        if ($vendorProfiles->isEmpty() || $categories->isEmpty() || $locations->isEmpty()) {
            $this->command->warn('Skipping AdInventorySeeder: Missing dependencies');
            return;
        }

        // Create inventory for each vendor
        foreach ($vendorProfiles as $vendor) {
            $inventoryCount = rand(3, 8);

            for ($i = 0; $i < $inventoryCount; $i++) {
                AdInventory::factory()
                    ->for($vendor, 'vendorProfile')
                    ->for($categories->random(), 'category')
                    ->for($locations->random(), 'location')
                    ->create();
            }
        }

        // Create additional random inventory using existing categories/locations (no AdCategory::factory)
        $extraCount = 10;
        for ($i = 0; $i < $extraCount; $i++) {
            AdInventory::factory()
                ->for($vendorProfiles->random(), 'vendorProfile')
                ->for($categories->random(), 'category')
                ->for($locations->random(), 'location')
                ->create();
        }
    }
}

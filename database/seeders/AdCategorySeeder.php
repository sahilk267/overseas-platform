<?php

namespace Database\Seeders;

use App\Models\AdCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdCategorySeeder extends Seeder
{
    public function run(): void
    {
        $hierarchy = [
            'Print Advertising' => [
                'Newspapers',
                'Magazines',
                'Brochures / Flyers',
            ],
            'Broadcast Advertising' => [
                'Television',
                'Radio',
                'Cinema',
            ],
            'Outdoor Advertising' => [
                'Billboards / Hoardings',
                'Bus Shelters',
                'Transit Ads',
                'Digital Outdoor Screens',
            ],
            'Digital / Online Advertising' => [
                'Social Media Ads',
                'Search Engine Ads',
                'Display & Banner Ads',
                'Video Ads (YouTube / OTT)',
                'Influencer Marketing',
            ],
            'Direct Advertising' => [
                'Email Marketing',
                'SMS / WhatsApp Ads',
                'Direct Mail',
            ],
        ];

        foreach ($hierarchy as $parentName => $children) {
            $parent = AdCategory::updateOrCreate(
            ['slug' => Str::slug($parentName)],
            ['name' => $parentName, 'description' => "Campaigns related to {$parentName}"]
            );

            foreach ($children as $childName) {
                AdCategory::updateOrCreate(
                ['slug' => Str::slug($childName)],
                [
                    'name' => $childName,
                    'parent_id' => $parent->id,
                    'description' => "Sub-category: {$childName} under {$parentName}"
                ]
                );
            }
        }
    }
}

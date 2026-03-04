<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Create major cities
        $cities = [
            ['name' => 'Mumbai, India', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'country' => 'India', 'latitude' => 19.0760, 'longitude' => 72.8777, 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Delhi, India', 'city' => 'Delhi', 'state' => 'Delhi', 'country' => 'India', 'latitude' => 28.6139, 'longitude' => 77.2090, 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Bangalore, India', 'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India', 'latitude' => 12.9716, 'longitude' => 77.5946, 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Hyderabad, India', 'city' => 'Hyderabad', 'state' => 'Telangana', 'country' => 'India', 'latitude' => 17.3850, 'longitude' => 78.4867, 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Chennai, India', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'country' => 'India', 'latitude' => 13.0827, 'longitude' => 80.2707, 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Kolkata, India', 'city' => 'Kolkata', 'state' => 'West Bengal', 'country' => 'India', 'latitude' => 22.5726, 'longitude' => 88.3639, 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Pune, India', 'city' => 'Pune', 'state' => 'Maharashtra', 'country' => 'India', 'latitude' => 18.5204, 'longitude' => 73.8567, 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Ahmedabad, India', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'country' => 'India', 'latitude' => 23.0225, 'longitude' => 72.5714, 'timezone' => 'Asia/Kolkata'],
        ];

        foreach ($cities as $city) {
            Location::create($city);
        }

        // Create additional random locations
        Location::factory(10)->create();
    }
}

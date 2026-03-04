<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('country', 100);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('timezone', 50)->default('UTC');
            $table->timestamps();

            // Indexes
            $table->index(['city', 'country'], 'idx_city_country');
            $table->index(['latitude', 'longitude'], 'idx_coordinates');
        });

        // Add CHECK constraints via raw SQL (MySQL 8 compatible)
        DB::statement('ALTER TABLE locations ADD CONSTRAINT chk_location_latitude CHECK (latitude >= -90 AND latitude <= 90)');
        DB::statement('ALTER TABLE locations ADD CONSTRAINT chk_location_longitude CHECK (longitude >= -180 AND longitude <= 180)');
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};

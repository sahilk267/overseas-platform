<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->string('stage_name', 255)->nullable();
            $table->json('specialties'); // Array of specialties (e.g., ['singing', 'dancing'])
            $table->unsignedInteger('experience_years')->default(0);
            $table->decimal('hourly_rate', 15, 2)->nullable();
            $table->char('currency', 3)->default('USD');
            $table->boolean('available_for_hire')->default(true);
            $table->text('portfolio_description')->nullable();
            $table->json('languages')->nullable(); // Array of languages spoken
            $table->timestamps();

            // Indexes
            $table->index('profile_id', 'idx_profile_id');
            $table->index('available_for_hire', 'idx_available_for_hire');
            $table->unique('profile_id', 'uk_profile_id');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE talent_profiles ADD CONSTRAINT chk_talent_experience CHECK (experience_years >= 0 AND experience_years <= 100)');
        DB::statement('ALTER TABLE talent_profiles ADD CONSTRAINT chk_talent_hourly_rate CHECK (hourly_rate IS NULL OR hourly_rate >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_profiles');
    }
};

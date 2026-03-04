<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('profile_type', ['advertiser', 'vendor', 'talent', 'event_organizer', 'admin']);
            $table->string('business_name', 255)->nullable();
            $table->string('display_name', 255);
            $table->text('bio')->nullable();
            $table->string('avatar', 500)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
            $table->string('website', 500)->nullable();
            $table->string('social_links', 1000)->nullable(); // JSON
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended', 'active'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('review_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'profile_type'], 'idx_user_profile_type');
            $table->index(['profile_type', 'status'], 'idx_type_status');
            $table->index('rating', 'idx_rating');
            $table->index('location_id', 'idx_location');
            $table->unique(['user_id', 'profile_type', 'deleted_at'], 'uk_user_profile_type');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE profiles ADD CONSTRAINT chk_profile_rating CHECK (rating >= 0 AND rating <= 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('reviewed_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->string('related_type', 50)->nullable(); // e.g., 'execution', 'appointment', 'event'
            $table->unsignedBigInteger('related_id')->nullable();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->text('response')->nullable(); // Reply from reviewed profile
            $table->timestamp('response_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            // Indexes
            $table->index(['reviewed_profile_id', 'rating'], 'idx_reviewed_rating');
            $table->index(['related_type', 'related_id'], 'idx_related');
            $table->index('reviewer_profile_id', 'idx_reviewer');
        });

        // Generated columns for UNIQUE constraint that handles NULLs
        DB::statement("ALTER TABLE reviews ADD COLUMN review_related_type_uk VARCHAR(50) GENERATED ALWAYS AS (COALESCE(related_type, 'global')) STORED");
        DB::statement("ALTER TABLE reviews ADD COLUMN review_related_id_uk BIGINT GENERATED ALWAYS AS (COALESCE(related_id, 0)) STORED");
        
        // UNIQUE constraint using generated columns
        DB::statement("ALTER TABLE reviews ADD UNIQUE KEY uk_reviewer_reviewed_related (reviewer_profile_id, reviewed_profile_id, review_related_type_uk, review_related_id_uk)");

        // CHECK constraints
        DB::statement('ALTER TABLE reviews ADD CONSTRAINT chk_review_rating CHECK (rating >= 1 AND rating <= 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

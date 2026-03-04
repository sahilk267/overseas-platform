<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->string('target_type', 50); // e.g., 'ad_campaign', 'profile', 'media_file'
            $table->unsignedBigInteger('target_id');
            $table->timestamp('assigned_at')->useCurrent();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->decimal('cost', 15, 2)->default(0); // Track cost per assignment
            $table->char('currency', 3)->default('USD');
            $table->timestamps();

            // Indexes
            $table->index(['promotion_id', 'status'], 'idx_promotion_status');
            $table->index(['target_type', 'target_id'], 'idx_target');
            $table->index(['promotion_id', 'cost'], 'idx_promotion_cost');
            $table->unique(['promotion_id', 'target_type', 'target_id'], 'uk_promotion_target');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE promotion_assignments ADD CONSTRAINT chk_promotion_assignment_cost CHECK (cost >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_assignments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->enum('promotion_type', ['featured', 'homepage_banner', 'category_spotlight', 'boost']);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 15, 2);
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'active', 'paused', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Indexes
            $table->index(['profile_id', 'status'], 'idx_profile_status');
            $table->index(['start_date', 'end_date'], 'idx_date_range');
            $table->index('promotion_type', 'idx_promotion_type');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE promotions ADD CONSTRAINT chk_promotion_budget CHECK (budget > 0)');
        DB::statement('ALTER TABLE promotions ADD CONSTRAINT chk_promotion_dates CHECK (end_date >= start_date)');
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};

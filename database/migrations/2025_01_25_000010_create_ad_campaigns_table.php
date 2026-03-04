<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 15, 2);
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'active', 'paused', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('profiles')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes - removed WHERE clause (MySQL 8 doesn't support partial indexes)
            $table->index(['advertiser_profile_id', 'deleted_at', 'status'], 'idx_advertiser_active');
            $table->index(['start_date', 'end_date'], 'idx_date_range');
            $table->index('status', 'idx_status');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE ad_campaigns ADD CONSTRAINT chk_ad_campaign_budget CHECK (budget > 0)');
        DB::statement('ALTER TABLE ad_campaigns ADD CONSTRAINT chk_ad_campaign_dates CHECK (end_date >= start_date)');
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_campaigns');
    }
};

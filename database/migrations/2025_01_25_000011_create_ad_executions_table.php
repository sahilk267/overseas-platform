<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('ad_campaigns')->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained('ad_inventory')->onDelete('restrict'); // Changed from SET NULL to RESTRICT
            $table->date('execution_date');
            $table->date('end_date');
            $table->decimal('cost', 15, 2);
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('idempotency_key', 64)->nullable()->unique(); // Prevents duplicate bookings
            $table->timestamps();

            // Indexes
            $table->index(['campaign_id', 'status'], 'idx_campaign_status');
            $table->index(['inventory_id', 'execution_date', 'end_date'], 'idx_inventory_dates');
            $table->index('execution_date', 'idx_execution_date');
            $table->index('idempotency_key', 'idx_idempotency');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE ad_executions ADD CONSTRAINT chk_ad_execution_cost CHECK (cost >= 0)');
        DB::statement('ALTER TABLE ad_executions ADD CONSTRAINT chk_ad_execution_dates CHECK (end_date >= execution_date)');
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_executions');
    }
};

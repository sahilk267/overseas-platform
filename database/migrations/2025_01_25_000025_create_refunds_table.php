<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('restrict');
            $table->foreignId('requested_by')->constrained('profiles')->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->char('currency', 3)->default('USD');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('transaction_id', 255)->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['payment_id', 'status'], 'idx_payment_status');
            $table->index('status', 'idx_status');
            $table->index('requested_by', 'idx_requested_by');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE refunds ADD CONSTRAINT chk_refund_amount CHECK (amount > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};

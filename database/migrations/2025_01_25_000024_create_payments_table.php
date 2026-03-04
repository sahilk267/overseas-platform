<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payer_profile_id')->constrained('profiles')->onDelete('restrict');
            $table->foreignId('recipient_profile_id')->constrained('profiles')->onDelete('restrict');
            $table->foreignId('execution_id')->nullable()->constrained('ad_executions')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->decimal('fees', 15, 2)->default(0);
            // net_amount as generated column (amount - fees)
            // Note: Laravel doesn't support generated columns via Blueprint, using raw SQL
            $table->char('currency', 3)->default('USD');
            $table->enum('payment_method', ['card', 'bank_transfer', 'paypal', 'wallet', 'cash']);
            $table->string('transaction_id', 255)->nullable()->unique();
            $table->string('idempotency_key', 64)->nullable()->unique();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'partially_refunded'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['recipient_profile_id', 'status', 'created_at'], 'idx_recipient_pending_created');
            $table->index(['payer_profile_id', 'status'], 'idx_payer_status');
            $table->index('execution_id', 'idx_execution');
            $table->index('invoice_id', 'idx_invoice');
            $table->index('idempotency_key', 'idx_idempotency');
        });

        // Add generated column for net_amount
        DB::statement('ALTER TABLE payments ADD COLUMN net_amount DECIMAL(15,2) GENERATED ALWAYS AS (amount - fees) STORED');

        // CHECK constraints
        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payment_amount CHECK (amount >= 0)');
        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payment_fees CHECK (fees >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

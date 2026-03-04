<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipient_profile_id')->constrained('profiles')->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->char('currency', 3)->default('USD');
            $table->decimal('rate', 5, 2); // Commission rate (e.g., 10.00 for 10%)
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['payment_id'], 'idx_payment_id');
            $table->index(['recipient_profile_id', 'status'], 'idx_recipient_status');
            $table->index('status', 'idx_status');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE commissions ADD CONSTRAINT chk_commission_amount CHECK (amount >= 0)');
        DB::statement('ALTER TABLE commissions ADD CONSTRAINT chk_commission_rate CHECK (rate >= 0 AND rate <= 100)');
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};

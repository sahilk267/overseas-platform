<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escrow_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['held', 'released', 'refunded'])->default('held');
            $table->text('hold_reason')->nullable();
            $table->date('release_date')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->foreignId('released_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes
            $table->index(['payment_id', 'status'], 'idx_payment_status');
            $table->index('release_date', 'idx_release_date');
            $table->index('status', 'idx_status');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE escrow_holds ADD CONSTRAINT chk_escrow_amount CHECK (amount > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('escrow_holds');
    }
};

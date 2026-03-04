<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('issuer_profile_id')->constrained('profiles')->onDelete('restrict');
            $table->foreignId('recipient_profile_id')->constrained('profiles')->onDelete('restrict');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'sent', 'paid', 'partially_paid', 'overdue', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['issuer_profile_id', 'status'], 'idx_issuer_status');
            $table->index(['recipient_profile_id', 'status'], 'idx_recipient_status');
            $table->index('invoice_date', 'idx_invoice_date');
            $table->index('due_date', 'idx_due_date');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE invoices ADD CONSTRAINT chk_invoice_subtotal CHECK (subtotal >= 0)');
        DB::statement('ALTER TABLE invoices ADD CONSTRAINT chk_invoice_tax CHECK (tax >= 0)');
        DB::statement('ALTER TABLE invoices ADD CONSTRAINT chk_invoice_total CHECK (total >= 0)');
        DB::statement('ALTER TABLE invoices ADD CONSTRAINT chk_invoice_due_date CHECK (due_date >= invoice_date)');
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

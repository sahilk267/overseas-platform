<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('description', 500);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('line_total', 15, 2); // quantity * unit_price
            $table->timestamps();

            // Indexes
            $table->index('invoice_id', 'idx_invoice_id');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE invoice_line_items ADD CONSTRAINT chk_invoice_line_quantity CHECK (quantity > 0)');
        DB::statement('ALTER TABLE invoice_line_items ADD CONSTRAINT chk_invoice_line_unit_price CHECK (unit_price >= 0)');
        DB::statement('ALTER TABLE invoice_line_items ADD CONSTRAINT chk_invoice_line_total CHECK (line_total >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_line_items');
    }
};

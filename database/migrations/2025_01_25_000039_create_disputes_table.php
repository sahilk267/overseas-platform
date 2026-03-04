<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complainant_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('respondent_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->string('related_type', 50); // e.g., 'payment', 'execution', 'contract'
            $table->unsignedBigInteger('related_id');
            $table->enum('dispute_type', ['payment', 'service_quality', 'non_delivery', 'fraud', 'contract_breach', 'other']);
            $table->text('description');
            $table->decimal('disputed_amount', 15, 2)->nullable();
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['open', 'under_review', 'resolved', 'closed'])->default('open');
            $table->enum('resolution', ['refund', 'partial_refund', 'no_action', 'other'])->nullable();
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['complainant_profile_id', 'status'], 'idx_complainant_status');
            $table->index(['respondent_profile_id', 'status'], 'idx_respondent_status');
            $table->index(['related_type', 'related_id'], 'idx_related');
            $table->index('status', 'idx_status');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE disputes ADD CONSTRAINT chk_dispute_amount CHECK (disputed_amount IS NULL OR disputed_amount >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};

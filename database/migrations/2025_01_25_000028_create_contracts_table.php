<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('party_a_profile_id')->constrained('profiles')->onDelete('restrict');
            $table->foreignId('party_b_profile_id')->constrained('profiles')->onDelete('restrict');
            $table->string('contract_type', 100); // e.g., 'service_agreement', 'ad_placement', 'talent_booking'
            $table->string('title', 255);
            $table->longText('terms'); // Full contract text
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'pending_signature', 'active', 'completed', 'terminated'])->default('draft');
            $table->timestamp('party_a_signed_at')->nullable();
            $table->timestamp('party_b_signed_at')->nullable();
            $table->string('party_a_signature', 500)->nullable(); // e.g., digital signature hash/path
            $table->string('party_b_signature', 500)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('parent_contract_id')->nullable()->constrained('contracts')->onDelete('set null');
            $table->timestamps();

            // Indexes
            $table->index(['party_a_profile_id', 'status'], 'idx_party_a_status');
            $table->index(['party_b_profile_id', 'status'], 'idx_party_b_status');
            $table->index('status', 'idx_status');
            $table->index('end_date', 'idx_end_date');
            $table->index('parent_contract_id', 'idx_parent_contract');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE contracts ADD CONSTRAINT chk_contract_dates CHECK (end_date IS NULL OR end_date >= start_date)');
        DB::statement('ALTER TABLE contracts ADD CONSTRAINT chk_contract_value CHECK (value IS NULL OR value >= 0)');
        DB::statement('ALTER TABLE contracts ADD CONSTRAINT chk_contract_version CHECK (version > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

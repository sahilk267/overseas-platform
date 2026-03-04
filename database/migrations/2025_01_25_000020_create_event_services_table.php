<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->enum('linked_type', ['talent', 'vendor', 'ad_execution']); // Updated to include ad_execution
            $table->unsignedBigInteger('linked_id'); // talent_profile_id, profile_id, or ad_execution_id
            $table->string('service_description', 500)->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['booked', 'confirmed', 'completed', 'cancelled'])->default('booked');
            $table->timestamps();

            // Indexes
            $table->index(['event_id', 'status'], 'idx_event_status');
            $table->index(['linked_type', 'linked_id'], 'idx_linked');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE event_services ADD CONSTRAINT chk_event_service_cost CHECK (cost IS NULL OR cost >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('event_services');
    }
};

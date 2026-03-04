<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('provider_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->datetime('scheduled_at');
            $table->datetime('end_at')->nullable();
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
            $table->string('meeting_type', 50)->default('in_person'); // in_person, virtual, phone
            $table->string('meeting_url', 500)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'no_show'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['provider_profile_id', 'scheduled_at', 'status'], 'idx_provider_scheduled_status');
            $table->index(['requester_profile_id', 'status'], 'idx_requester_status');
            $table->index('scheduled_at', 'idx_scheduled_at');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE appointments ADD CONSTRAINT chk_appointment_end_time CHECK (end_at IS NULL OR end_at > scheduled_at)');
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

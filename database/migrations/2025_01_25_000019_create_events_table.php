<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->enum('event_type', ['conference', 'concert', 'exhibition', 'wedding', 'corporate', 'sports', 'other']);
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
            $table->string('venue_name', 255)->nullable();
            $table->text('venue_address')->nullable();
            $table->unsignedInteger('expected_attendees')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'published', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['organizer_profile_id', 'status'], 'idx_organizer_status');
            $table->index(['start_datetime', 'end_datetime'], 'idx_datetime_range');
            $table->index('event_type', 'idx_event_type');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE events ADD CONSTRAINT chk_event_datetime CHECK (end_datetime > start_datetime)');
        DB::statement('ALTER TABLE events ADD CONSTRAINT chk_event_budget CHECK (budget IS NULL OR budget >= 0)');
        DB::statement('ALTER TABLE events ADD CONSTRAINT chk_event_attendees CHECK (expected_attendees IS NULL OR expected_attendees > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

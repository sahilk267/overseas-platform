<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availability_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['profile_id', 'date', 'is_available'], 'idx_profile_date_availability');
            $table->index(['date', 'start_time'], 'idx_date_time');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE availability_slots ADD CONSTRAINT chk_availability_time CHECK (end_time > start_time)');
    }

    public function down(): void
    {
        Schema::dropIfExists('availability_slots');
    }
};

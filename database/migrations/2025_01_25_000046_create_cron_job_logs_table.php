<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cron_job_logs', function (Blueprint $table) {
            $table->id();
            $table->string('job_name', 255);
            $table->enum('status', ['started', 'success', 'failed'])->default('started');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('duration_ms')->nullable(); // milliseconds
            $table->text('output')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['job_name', 'started_at'], 'idx_job_name_started');
            $table->index('status', 'idx_status');
            $table->index('started_at', 'idx_started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cron_job_logs');
    }
};

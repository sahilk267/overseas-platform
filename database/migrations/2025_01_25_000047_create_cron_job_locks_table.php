<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cron_job_locks', function (Blueprint $table) {
            $table->id();
            $table->string('job_name', 255)->unique();
            $table->timestamp('locked_at')->useCurrent();
            $table->timestamp('expires_at');
            $table->string('lock_id', 64); // Unique identifier for this lock instance

            // Indexes
            $table->index(['job_name', 'expires_at'], 'idx_job_name_expires');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cron_job_locks');
    }
};

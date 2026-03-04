<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->enum('report_type', ['financial', 'user_activity', 'campaign_performance', 'system_health', 'compliance', 'custom']);
            $table->text('description')->nullable();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('requested_by_profile_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->json('parameters')->nullable(); // Report filter params
            $table->enum('status', ['pending', 'generating', 'completed', 'failed'])->default('pending');
            $table->string('file_path', 1000)->nullable(); // Updated from 500 to 1000
            $table->string('format', 20)->default('pdf'); // pdf, csv, xlsx
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['requested_by_profile_id', 'report_type'], 'idx_requested_by_profile_type');
            $table->index(['requested_by_user_id', 'report_type'], 'idx_requested_by_user_type');
            $table->index(['status', 'created_at'], 'idx_status_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_breaches', function (Blueprint $table) {
            $table->id();
            $table->string('incident_id', 100)->unique();
            $table->timestamp('detected_at');
            $table->enum('breach_type', ['data_leak', 'unauthorized_access', 'malware', 'dos', 'phishing', 'other']);
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('description');
            $table->text('affected_data_types')->nullable(); // JSON
            $table->unsignedInteger('affected_users_count')->default(0);
            $table->enum('status', ['detected', 'investigating', 'contained', 'resolved'])->default('detected');
            $table->boolean('authority_notified')->default(false);
            $table->timestamp('authority_notified_at')->nullable();
            $table->boolean('users_notified')->default(false);
            $table->timestamp('users_notified_at')->nullable();
            $table->text('response_actions')->nullable();
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('incident_id', 'idx_incident_id');
            $table->index(['severity', 'status'], 'idx_severity_status');
            $table->index('detected_at', 'idx_detected_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_breaches');
    }
};

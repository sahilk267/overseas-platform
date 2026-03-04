<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_subject_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('request_type', ['access', 'rectification', 'erasure', 'restriction', 'portability', 'objection']);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->text('response')->nullable();
            $table->string('export_file_path', 500)->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'request_type', 'status'], 'idx_user_request_type_status');
            $table->index('status', 'idx_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_subject_requests');
    }
};

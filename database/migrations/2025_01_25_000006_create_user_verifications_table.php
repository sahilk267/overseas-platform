<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('verification_type', ['email', 'phone', 'identity', 'business', 'address', 'payment']);
            $table->enum('status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');
            $table->string('document_type', 50)->nullable(); // e.g., 'passport', 'drivers_license', 'utility_bill'
            $table->string('document_path', 500)->nullable();
            $table->text('metadata')->nullable(); // JSON for additional data
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'verification_type'], 'idx_user_verification_type');
            $table->index('status', 'idx_status');
            $table->index('expires_at', 'idx_expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_verifications');
    }
};

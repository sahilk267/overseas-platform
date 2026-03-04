<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('activity_type', ['login', 'logout', 'profile_view', 'search', 'booking', 'payment', 'message', 'review', 'other']);
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Additional context
            $table->timestamps();

            // Indexes
            $table->index(['profile_id', 'created_at'], 'idx_profile_created');
            $table->index(['activity_type', 'created_at'], 'idx_activity_type_created');
            $table->index('created_at', 'idx_created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

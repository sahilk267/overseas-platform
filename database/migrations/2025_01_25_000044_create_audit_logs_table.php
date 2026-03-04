<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('profile_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action', 100); // e.g., 'create', 'update', 'delete', 'login', 'logout'
            $table->string('entity_type', 100)->nullable(); // e.g., 'ad_campaign', 'payment'
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'created_at'], 'idx_user_created');
            $table->index(['profile_id', 'created_at'], 'idx_profile_created');
            $table->index(['entity_type', 'entity_id'], 'idx_entity');
            $table->index('action', 'idx_action');
            $table->index('created_at', 'idx_created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

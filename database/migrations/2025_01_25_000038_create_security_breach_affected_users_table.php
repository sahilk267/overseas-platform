<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_breach_affected_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breach_id')->constrained('security_breaches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('affected_data_fields')->nullable(); // JSON array
            $table->boolean('notified')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['breach_id', 'user_id'], 'idx_breach_user');
            $table->index(['user_id', 'notified'], 'idx_user_notified');
            $table->unique(['breach_id', 'user_id'], 'uk_breach_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_breach_affected_users');
    }
};

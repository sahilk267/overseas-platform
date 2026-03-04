<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->enum('notification_type', ['system', 'payment', 'message', 'booking', 'review', 'promotion']);
            $table->string('title', 255);
            $table->text('body');
            $table->string('action_url', 500)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['profile_id', 'is_read', 'created_at'], 'idx_profile_unread_created');
            $table->index('notification_type', 'idx_notification_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

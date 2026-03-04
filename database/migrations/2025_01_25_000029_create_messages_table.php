<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('receiver_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->string('subject', 500)->nullable();
            $table->text('body');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->foreignId('parent_message_id')->nullable()->constrained('messages')->onDelete('set null'); // For threading
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['receiver_profile_id', 'is_read', 'created_at'], 'idx_receiver_unread_created');
            $table->index(['sender_profile_id', 'created_at'], 'idx_sender_created');
            $table->index('parent_message_id', 'idx_parent_message');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispute_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispute_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_profile_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->foreignId('sender_user_id')->nullable()->constrained('users')->onDelete('set null'); // For admin/moderator messages
            $table->text('message');
            $table->boolean('is_internal')->default(false); // Internal notes vs. visible to parties
            $table->timestamps();

            // Indexes
            $table->index(['dispute_id', 'created_at'], 'idx_dispute_created');
            $table->index('sender_profile_id', 'idx_sender_profile');
            $table->index('sender_user_id', 'idx_sender_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispute_messages');
    }
};

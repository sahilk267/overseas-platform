<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_controls', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->enum('action', ['whitelist', 'blacklist'])->default('blacklist');
            $table->text('reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes
            $table->index(['ip_address', 'action'], 'idx_ip_action');
            $table->index('expires_at', 'idx_expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_controls');
    }
};

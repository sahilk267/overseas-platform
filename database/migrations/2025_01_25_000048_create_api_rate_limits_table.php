<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('identifier', 255); // IP address or user/profile ID
            $table->enum('identifier_type', ['ip', 'user', 'profile'])->default('ip');
            $table->string('endpoint', 255)->nullable(); // Specific API endpoint (null = global)
            $table->unsignedInteger('hits')->default(1);
            $table->timestamp('window_start')->useCurrent();
            $table->timestamp('window_end');
            $table->timestamps();

            // Indexes
            $table->index(['identifier', 'endpoint', 'window_end'], 'idx_identifier_endpoint_window');
            $table->index('window_end', 'idx_window_end');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_rate_limits');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('consent_type', ['terms', 'privacy', 'marketing', 'cookies', 'data_processing']);
            $table->boolean('consented')->default(false);
            $table->timestamp('consented_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'consent_type', 'consented'], 'idx_user_consent_type_status');
            $table->index('consented_at', 'idx_consented_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consents');
    }
};

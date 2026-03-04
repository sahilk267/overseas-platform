<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('lead_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('ad_campaigns')->onDelete('cascade');
            $table->foreignId('vendor_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'passed', 'expired'])->default('pending');
            $table->timestamp('notified_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['campaign_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_notifications');
    }
};

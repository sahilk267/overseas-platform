<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_type', 100); // e.g., 'page_view', 'click', 'conversion', 'revenue'
            $table->string('category', 100)->nullable(); // e.g., 'campaigns', 'user_engagement'
            $table->string('entity_type', 50)->nullable(); // e.g., 'ad_campaign', 'profile'
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->decimal('value', 20, 4);
            $table->string('unit', 50)->nullable(); // e.g., 'USD', 'clicks', 'views'
            $table->json('metadata')->nullable(); // Additional context
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            // Indexes
            $table->index(['metric_type', 'recorded_at'], 'idx_metric_type_recorded');
            $table->index(['entity_type', 'entity_id'], 'idx_entity');
            $table->index('recorded_at', 'idx_recorded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metrics');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_portfolio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_profile_id')->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->foreignId('media_id')->constrained('media_files')->onDelete('cascade');
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            // Indexes
            $table->index(['talent_profile_id', 'display_order'], 'idx_talent_display_order');
            $table->index('is_featured', 'idx_is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_portfolio');
    }
};

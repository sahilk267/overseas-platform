<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['system', 'profile', 'content', 'financial', 'moderation'])->default('profile');
            $table->timestamps();

            // Indexes
            $table->index('category', 'idx_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

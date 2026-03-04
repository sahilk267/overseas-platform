<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('category_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('ad_categories')->onDelete('cascade');
            $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade');
            $table->enum('role_level', ['admin', 'sub_admin'])->default('sub_admin');
            $table->timestamps();

            $table->unique(['category_id', 'profile_id', 'role_level'], 'idx_cat_prof_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_profiles');
    }
};

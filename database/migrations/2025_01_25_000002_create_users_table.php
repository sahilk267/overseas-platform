<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('phone', 20)->nullable()->unique();
            $table->enum('status', ['active', 'suspended', 'deleted'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret', 255)->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status', 'idx_status');
            $table->index('created_at', 'idx_created_at');
        });

        // CHECK constraints
        DB::statement("ALTER TABLE users ADD CONSTRAINT chk_user_email CHECK (email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$')");
        DB::statement("ALTER TABLE users ADD CONSTRAINT chk_user_phone CHECK (phone IS NULL OR phone REGEXP '^[+]?[0-9]{10,15}$')");
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

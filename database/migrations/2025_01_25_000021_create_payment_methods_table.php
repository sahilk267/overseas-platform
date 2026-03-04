<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->enum('method_type', ['card', 'bank_account', 'paypal', 'wallet']);
            $table->string('provider', 100)->nullable(); // e.g., 'stripe', 'paypal', 'razorpay'
            $table->string('provider_id', 255)->nullable(); // Provider's payment method ID
            $table->string('last_four', 4)->nullable();
            $table->string('brand', 50)->nullable(); // e.g., 'Visa', 'Mastercard'
            $table->unsignedTinyInteger('exp_month')->nullable();
            $table->unsignedSmallInteger('exp_year')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            // Indexes
            $table->index(['profile_id', 'is_default'], 'idx_profile_default');
            $table->index('provider_id', 'idx_provider_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

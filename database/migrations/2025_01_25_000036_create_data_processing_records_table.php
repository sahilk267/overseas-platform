<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_processing_records', function (Blueprint $table) {
            $table->id();
            $table->string('processing_purpose', 255);
            $table->text('data_categories'); // JSON array
            $table->text('legal_basis');
            $table->text('recipients')->nullable();
            $table->string('retention_period', 100);
            $table->text('security_measures')->nullable();
            $table->foreignId('data_controller_user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            // Indexes
            $table->index('data_controller_user_id', 'idx_data_controller');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_processing_records');
    }
};

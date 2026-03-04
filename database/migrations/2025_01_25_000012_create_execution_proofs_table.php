<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('execution_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_id')->constrained('ad_executions')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('profiles')->onDelete('cascade');
            $table->enum('proof_type', ['photo', 'video', 'document', 'geolocation']);
            $table->foreignId('media_id')->nullable()->constrained('media_files')->onDelete('set null');
            $table->text('description')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('captured_at')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('profiles')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['execution_id', 'status'], 'idx_execution_status');
            $table->index('proof_type', 'idx_proof_type');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE execution_proofs ADD CONSTRAINT chk_execution_proof_latitude CHECK (latitude IS NULL OR (latitude >= -90 AND latitude <= 90))');
        DB::statement('ALTER TABLE execution_proofs ADD CONSTRAINT chk_execution_proof_longitude CHECK (longitude IS NULL OR (longitude >= -180 AND longitude <= 180))');
    }

    public function down(): void
    {
        Schema::dropIfExists('execution_proofs');
    }
};

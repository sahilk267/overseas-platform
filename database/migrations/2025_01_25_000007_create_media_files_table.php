<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('media_type', ['image', 'video', 'document', 'audio']);
            $table->string('path', 1000); // Updated from 500 to 1000
            $table->string('filename', 255);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size'); // bytes
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('duration')->nullable(); // seconds for video/audio
            $table->string('thumbnail_path', 1000)->nullable();
            $table->text('metadata')->nullable(); // JSON
            $table->enum('status', ['active', 'deleted'])->default('active');
            $table->timestamps();

            // Indexes
            $table->index(['profile_id', 'media_type'], 'idx_profile_media_type');
            $table->index('created_at', 'idx_created_at');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE media_files ADD CONSTRAINT chk_media_file_size CHECK (file_size > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};

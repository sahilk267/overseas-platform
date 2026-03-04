<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('ad_categories')->onDelete('restrict');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('inventory_type', ['billboard', 'digital_screen', 'banner', 'poster', 'vehicle']);
            $table->string('dimensions', 50)->nullable(); // e.g., "10x20 feet"
            $table->foreignId('location_id')->constrained()->onDelete('restrict');
            $table->decimal('price_per_day', 15, 2);
            $table->char('currency', 3)->default('USD');
            $table->unsignedInteger('min_booking_days')->default(1);
            $table->boolean('requires_approval')->default(false);
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['vendor_profile_id', 'status'], 'idx_vendor_status');
            $table->index(['category_id', 'location_id'], 'idx_category_location');
            $table->index('inventory_type', 'idx_inventory_type');
        });

        // CHECK constraints
        DB::statement('ALTER TABLE ad_inventory ADD CONSTRAINT chk_ad_inventory_price CHECK (price_per_day > 0)');
        DB::statement('ALTER TABLE ad_inventory ADD CONSTRAINT chk_ad_inventory_min_booking CHECK (min_booking_days > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_inventory');
    }
};

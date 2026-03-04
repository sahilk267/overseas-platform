<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('advertiser_profile_id')->constrained('ad_categories')->onDelete('set null');
            $table->integer('progress_percentage')->default(0)->after('status');
            $table->string('last_status_update', 500)->nullable()->after('progress_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->string('target_city')->nullable()->after('category_id');
            $table->text('address_details')->nullable()->after('target_city');
            $table->string('campaign_goal')->nullable()->after('name'); // e.g. Awareness, Calls, Visits
            $table->text('brief')->nullable()->after('description'); // Detailed execution notes
        });
    }

    public function down(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->dropColumn(['target_city', 'address_details', 'campaign_goal', 'brief']);
        });
    }
};

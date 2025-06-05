<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('report_schedule_stakeholder', function (Blueprint $table) {
            $table->json('filters')->nullable();
            $table->json('delivery_channels')->nullable();
            $table->string('preferred_format')->nullable();
        });
    }

    public function down()
    {
        Schema::table('report_schedule_stakeholder', function (Blueprint $table) {
            $table->dropColumn(['filters', 'delivery_channels', 'preferred_format']);
        });
    }
}; 
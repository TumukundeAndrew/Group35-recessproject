<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('report_schedules', function (Blueprint $table) {
            $table->boolean('enable_caching')->default(false);
            $table->integer('cache_duration')->default(24); // in hours
            $table->json('available_formats')->default(json_encode(['html', 'pdf', 'csv']));
            $table->boolean('notify_on_failure')->default(true);
        });
    }

    public function down()
    {
        Schema::table('report_schedules', function (Blueprint $table) {
            $table->dropColumn(['enable_caching', 'cache_duration', 'available_formats', 'notify_on_failure']);
        });
    }
}; 
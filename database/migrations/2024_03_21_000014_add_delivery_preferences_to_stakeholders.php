<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stakeholders', function (Blueprint $table) {
            $table->json('delivery_preferences')->nullable();
            $table->string('preferred_format')->default('html');
            $table->string('locale')->default('en');
            $table->string('timezone')->default('UTC');
            $table->json('api_config')->nullable(); // For API delivery settings
            $table->string('webhook_url')->nullable();
            $table->string('phone')->nullable()->change(); // Make phone nullable if not already
        });
    }

    public function down()
    {
        Schema::table('stakeholders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_preferences',
                'preferred_format',
                'locale',
                'timezone',
                'api_config',
                'webhook_url'
            ]);
            $table->string('phone')->nullable(false)->change();
        });
    }
}; 
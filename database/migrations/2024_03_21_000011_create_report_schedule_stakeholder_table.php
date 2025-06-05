<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('report_schedule_stakeholder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('stakeholder_id')->constrained()->onDelete('cascade');
            $table->json('customizations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_schedule_stakeholder');
    }
}; 
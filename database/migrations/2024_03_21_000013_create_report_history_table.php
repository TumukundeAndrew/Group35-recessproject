<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('report_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('stakeholder_id')->constrained()->onDelete('cascade');
            $table->string('status'); // sent, failed, pending
            $table->text('content_path')->nullable(); // Path to stored report
            $table->text('error_message')->nullable();
            $table->string('format')->default('html'); // html, pdf, csv
            $table->json('delivery_channels')->nullable(); // ['email', 'api', 'sms', 'webhook']
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_history');
    }
}; 
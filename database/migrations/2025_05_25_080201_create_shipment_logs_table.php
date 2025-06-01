<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('shipment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('from_location'); // e.g., factory
            $table->string('to_location'); // e.g., warehouse
            $table->date('shipment_date');
            $table->string('status'); // shipped, delivered
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('shipment_logs');
    }
};

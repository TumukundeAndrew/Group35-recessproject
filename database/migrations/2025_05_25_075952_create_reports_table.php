<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('stakeholder_id')->nullable();
            $table->string('report_type'); // e.g., sales, inventory
            $table->string('file_path')->nullable(); // Path to the PDF file
            $table->date('scheduled_date');
            $table->string('status')->default('pending'); // pending, sent
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('stakeholder_id')->references('id')->on('stakeholders')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('reports');
    }
};

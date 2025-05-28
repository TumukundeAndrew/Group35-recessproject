<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('financial_stability', 15, 2);
            $table->string('reputation_score'); // e.g., 1-5
            $table->boolean('regulatory_compliance')->default(false);
            $table->string('pdf_path')->nullable();
            $table->string('application_status')->default('pending'); // pending, under_review, approved
            $table->date('visit_date')->nullable();
            $table->string('visit_status')->nullable(); // scheduled, completed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}; 
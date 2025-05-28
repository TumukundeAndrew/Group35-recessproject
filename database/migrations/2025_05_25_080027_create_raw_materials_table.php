<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit'); // e.g., kg
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('raw_materials');
    }
};

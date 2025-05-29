<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_type')->after('status')->nullable();
            // Also add vendor_id if it's missing
            if (!Schema::hasColumn('orders', 'vendor_id')) {
                $table->foreignId('vendor_id')->nullable()->after('supplier_id')->constrained('users')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_type');
            if (Schema::hasColumn('orders', 'vendor_id')) {
                $table->dropForeign(['vendor_id']);
                $table->dropColumn('vendor_id');
            }
        });
    }
}; 
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_containers', function (Blueprint $table) {
            $table->integer('low_stock_threshold')->default(20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_containers', function (Blueprint $table) {
            $table->dropColumn('low_stock_threshold');
        });
    }
};

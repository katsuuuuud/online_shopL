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
        Schema::table('product_colors', function (Blueprint $table) {
            $table->foreign(['product_id'], 'fk_product_colors_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['color_id'], 'fk_product_colors_color')->references(['colorId'])->on('colors')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_colors', function (Blueprint $table) {
            $table->dropForeign('fk_product_colors_product');
            $table->dropForeign('fk_product_colors_color');
        });
    }
};

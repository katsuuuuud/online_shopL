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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign(['cart_id'], 'fk_cart_items_cart')->references(['cartId'])->on('carts')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['product_id'], 'fk_cart_items_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign('fk_cart_items_cart');
            $table->dropForeign('fk_cart_items_product');
        });
    }
};

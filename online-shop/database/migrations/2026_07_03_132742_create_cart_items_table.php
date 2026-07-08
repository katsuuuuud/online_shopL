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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->integer('cart_item_id', true);
            $table->integer('cart_id');
            $table->integer('product_id')->index('fk_cart_items_product');
            $table->integer('quantity');
            $table->decimal('price', 10);
            $table->string('currency', 10)->default('USD');

            $table->unique(['cart_id', 'product_id'], 'uniq_cart_product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};

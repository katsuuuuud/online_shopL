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
        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['category_id'], 'fk_products_category')->references(['categoryId'])->on('categories')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['discount_id'], 'fk_products_discount')->references(['discountId'])->on('discounts')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('fk_products_category');
            $table->dropForeign('fk_products_discount');
        });
    }
};

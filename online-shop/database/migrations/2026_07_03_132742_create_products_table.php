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
        Schema::create('products', function (Blueprint $table) {
            $table->integer('productId', true);
            $table->string('name', 50);
            $table->string('description', 100)->nullable();
            $table->integer('category_id')->index('fk_products_category');
            $table->integer('discount_id')->nullable()->index('fk_products_discount');
            $table->boolean('has_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

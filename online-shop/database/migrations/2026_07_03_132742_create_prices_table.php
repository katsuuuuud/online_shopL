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
        Schema::create('prices', function (Blueprint $table) {
            $table->integer('priceauditId', true);
            $table->integer('product_id')->index('fk_prices_product');
            $table->decimal('price', 10);
            $table->string('currency', 10);
            $table->boolean('is_active')->nullable()->default(false);
            $table->date('date')->nullable()->default('2025-09-11');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};

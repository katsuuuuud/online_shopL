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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('product_id');

            $table->unique(['user_id', 'product_id']);

            $table->foreign('user_id')
                ->references('userId')->on('users')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('productId')->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};

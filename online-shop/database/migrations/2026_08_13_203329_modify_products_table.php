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
            $table->dropForeign('fk_cart_items_product');
        });
        Schema::table('prices', function (Blueprint $table) {
            $table->dropForeign('fk_prices_product');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->increments('productId')->change();
            $table->string('name', 100)->change();
            $table->text('description')->nullable()->change();
        });

        Schema::table('product_audit', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->change();
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->change();
        });
        Schema::table('prices', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->change();
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->change();
        });
        Schema::table('favorites', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->change();
        });

        Schema::table('product_audit', function (Blueprint $table) {
            $table->foreign(['product_id'], 'fk_product_audit_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign(['product_id'], 'fk_cart_items_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
        Schema::table('prices', function (Blueprint $table) {
            $table->foreign(['product_id'], 'fk_prices_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('productId')->on('products')->onDelete('cascade');
        });
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreign('product_id')->references('productId')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_audit', function (Blueprint $table) {
            $table->dropForeign('fk_product_audit_product');
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign('fk_cart_items_product');
        });
        Schema::table('prices', function (Blueprint $table) {
            $table->dropForeign('fk_prices_product');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('productId', true)->change();
            $table->string('name', 50)->change();
            $table->string('description', 100)->nullable()->change();
        });

        Schema::table('product_audit', function (Blueprint $table) {
            $table->integer('product_id')->change();
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->integer('product_id')->change();
        });
        Schema::table('prices', function (Blueprint $table) {
            $table->integer('product_id')->change();
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('product_id')->change();
        });
        Schema::table('favorites', function (Blueprint $table) {
            $table->integer('product_id')->change();
        });

        Schema::table('product_audit', function (Blueprint $table) {
            $table->foreign(['product_id'], 'fk_product_audit_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign(['product_id'], 'fk_cart_items_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
        Schema::table('prices', function (Blueprint $table) {
            $table->foreign(['product_id'], 'fk_prices_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('productId')->on('products')->onDelete('cascade');
        });
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreign('product_id')->references('productId')->on('products')->onDelete('cascade');
        });
    }
};

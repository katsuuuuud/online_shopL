<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // cart_items: product_id -> variant_id
        if (Schema::hasColumn('cart_items', 'product_id')) {
            try {
                Schema::table('cart_items', function (Blueprint $table) {
                    $table->dropForeign('fk_cart_items_product');
                });
            } catch (\Throwable $e) {
                //
            }

            try {
                Schema::table('cart_items', function (Blueprint $table) {
                    $table->dropUnique('uniq_cart_product');
                });
            } catch (\Throwable $e) {
                //
            }

            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }

        // existing cart rows can't be mapped to a specific variant, so clear them
        DB::table('cart_items')->delete();

        if (!Schema::hasColumn('cart_items', 'variant_id')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->unsignedInteger('variant_id')->index('fk_cart_items_variant');
            });
        }

        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['cart_id', 'variant_id'], 'uniq_cart_variant');
        });

        try {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->foreign(['variant_id'], 'fk_cart_items_variant')->references(['variantId'])->on('product_variants')->onUpdate('no action')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
            //
        }

        // order_items: product_id -> variant_id
        if (Schema::hasColumn('order_items', 'product_id')) {
            try {
                Schema::table('order_items', function (Blueprint $table) {
                    $table->dropForeign(['product_id']);
                });
            } catch (\Throwable $e) {
                //
            }

            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }

        DB::table('order_items')->delete();

        if (!Schema::hasColumn('order_items', 'variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->unsignedInteger('variant_id')->index('fk_order_items_variant');
            });
        }

        try {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreign(['variant_id'], 'fk_order_items_variant')->references(['variantId'])->on('product_variants')->onUpdate('no action')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
            //
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropForeign('fk_cart_items_variant');
            });
        } catch (\Throwable $e) {
            //
        }
        try {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropUnique('uniq_cart_variant');
            });
        } catch (\Throwable $e) {
            //
        }
        if (Schema::hasColumn('cart_items', 'variant_id')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropColumn('variant_id');
            });
        }
        DB::table('cart_items')->delete();
        if (!Schema::hasColumn('cart_items', 'product_id')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->integer('product_id')->index('fk_cart_items_product');
            });
        }
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['cart_id', 'product_id'], 'uniq_cart_product');
        });
        try {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->foreign(['product_id'], 'fk_cart_items_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
            //
        }

        try {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign('fk_order_items_variant');
            });
        } catch (\Throwable $e) {
            //
        }
        if (Schema::hasColumn('order_items', 'variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('variant_id');
            });
        }
        DB::table('order_items')->delete();
        if (!Schema::hasColumn('order_items', 'product_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->integer('product_id');
            });
        }
        try {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreign('product_id')->references('productId')->on('products')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
            //
        }
    }
};

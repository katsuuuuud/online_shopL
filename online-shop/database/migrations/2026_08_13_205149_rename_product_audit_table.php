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
        if (Schema::hasTable('product_audit') && !Schema::hasTable('stock')) {
            Schema::rename('product_audit', 'stock');
        }

        if (Schema::hasColumn('stock', 'product_id')) {
            try {
                Schema::table('stock', function (Blueprint $table) {
                    $table->dropForeign('fk_product_audit_product');
                });
            } catch (\Throwable $e) {
            }

            Schema::table('stock', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }

        if (Schema::hasColumn('stock', 'auditId')) {
            Schema::table('stock', function (Blueprint $table) {
                $table->renameColumn('auditId', 'stockId');
            });
        }

        Schema::table('stock', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->default(0)->change();
        });

        if (!Schema::hasColumn('stock', 'variant_id')) {
            Schema::table('stock', function (Blueprint $table) {
                $table->unsignedInteger('variant_id')->index('fk_stock_variant');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('stock', 'variant_id')) {
            Schema::table('stock', function (Blueprint $table) {
                $table->dropColumn('variant_id');
            });
        }

        Schema::table('stock', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->change();
        });

        if (Schema::hasColumn('stock', 'stockId')) {
            Schema::table('stock', function (Blueprint $table) {
                $table->renameColumn('stockId', 'auditId');
            });
        }

        if (!Schema::hasColumn('stock', 'product_id')) {
            Schema::table('stock', function (Blueprint $table) {
                $table->integer('product_id')->index('fk_product_audit_product');
            });

            try {
                Schema::table('stock', function (Blueprint $table) {
                    $table->foreign(['product_id'], 'fk_product_audit_product')->references(['productId'])->on('products')->onUpdate('no action')->onDelete('cascade');
                });
            } catch (\Throwable $e) {
                //
            }
        }

        if (Schema::hasTable('stock') && !Schema::hasTable('product_audit')) {
            Schema::rename('stock', 'product_audit');
        }
    }
};

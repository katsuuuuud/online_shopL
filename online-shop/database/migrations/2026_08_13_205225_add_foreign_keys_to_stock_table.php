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
        if (!Schema::hasColumn('stock', 'variant_id')) {
            Schema::table('stock', function (Blueprint $table) {
                $table->unsignedInteger('variant_id')->index('fk_stock_variant');
            });
        }

        DB::table('stock')->delete();

        try {
            Schema::table('stock', function (Blueprint $table) {
                $table->foreign(['variant_id'], 'fk_stock_variant')->references(['variantId'])->on('product_variants')->onUpdate('no action')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('stock', function (Blueprint $table) {
                $table->dropForeign('fk_stock_variant');
            });
        } catch (\Throwable $e) {
            //
        }
    }
};

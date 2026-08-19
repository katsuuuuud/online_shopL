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
        if (!Schema::hasColumn('products', 'has_variant')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('has_variant')->default(false)->after('discount_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'has_variant')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('has_variant');
            });
        }
    }
};

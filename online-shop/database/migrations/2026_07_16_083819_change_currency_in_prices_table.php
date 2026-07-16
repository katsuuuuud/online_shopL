<?php

use App\Models\Discount;
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
        DB::table('prices')
            ->where('currency', 'USD')
            ->update(['currency' => 'KZT']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('prices')
            ->where('currency', 'KZT')
            ->update(['currency' => 'USD']);
    }
};

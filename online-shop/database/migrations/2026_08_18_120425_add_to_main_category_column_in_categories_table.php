<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE categories MODIFY main_category ENUM('women', 'men', 'unisex') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE categories MODIFY main_category ENUM('women', 'men', 'kids') NULL");
    }
};

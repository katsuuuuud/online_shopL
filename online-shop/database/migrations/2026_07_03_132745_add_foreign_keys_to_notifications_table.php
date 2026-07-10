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
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign(['customer_id'], 'fk_notifications_customer')->references(['userId'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['order_id'], 'fk_notifications_order')->references(['orderId'])->on('orders')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign('fk_notifications_customer');
            $table->dropForeign('fk_notifications_order');
        });
    }
};

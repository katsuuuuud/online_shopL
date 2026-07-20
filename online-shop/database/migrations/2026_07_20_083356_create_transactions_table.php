<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->string('invoice_id')->unique();
            $table->string('epay_transaction_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('approval_code')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('KZT');
            $table->string('status')->default('pending');
            $table->string('card_mask')->nullable();
            $table->string('card_type')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

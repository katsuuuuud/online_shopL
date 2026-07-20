<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaction_id')
                ->constrained('transactions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('event_type');
            $table->string('direction');
            $table->integer('http_status')->nullable();
            $table->json('request_payload')->nullable();
            $table->boolean('signature_valid')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->index('transaction_id');
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->enum('payment_method', ['qris', 'cash']);
            $table->string('midtrans_order_id')->unique()->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'expired', 'refunded'])->default('pending')->index();
            $table->text('qris_url')->nullable();
            $table->dateTime('transaction_time')->nullable();
            $table->dateTime('settlement_time')->nullable();
            $table->text('refund_proof')->nullable();
            $table->text('refund_reason')->nullable();
            $table->dateTime('refund_time')->nullable();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

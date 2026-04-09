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
            // Method
            $table->enum('payment_method', ['qris', 'cash'])->index();
            $table->decimal('amount', 12, 2);
            // Status
            $table->enum('status', ['pending', 'success', 'failed', 'expired', 'refunded'])->default('pending')->index();
            // Midtrans Data
            $table->text('qris_url')->nullable();
            $table->dateTime('transaction_time')->nullable();
            $table->dateTime('settlement_time')->nullable();
            // Refund
            $table->text('refund_proof')->nullable();
            $table->text('refund_reason')->nullable();
            $table->dateTime('refund_time')->nullable();
            // Relation
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // Unique Constraint
            $table->unique('order_id');
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

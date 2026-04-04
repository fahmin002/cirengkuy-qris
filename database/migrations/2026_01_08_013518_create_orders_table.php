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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id');
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('delivery_type', ['pickup', 'delivery'])->index();
            $table->enum('order_type', ['frozen', 'cooked'])->default('cooked');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending')->index();
            $table->enum('payment_status', ['paid', 'unpaid']);
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

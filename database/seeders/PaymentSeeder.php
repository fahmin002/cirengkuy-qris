<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::take(3)->get();

        if ($orders->count() < 3) {
            $this->command->warn('Minimal butuh 3 order untuk seeder Payment');
            return;
        }

        // 1️⃣ SUCCESS PAYMENT
        Payment::create([
            'order_id' => $orders[0]->id,
            'payment_method' => 'qris',
            'amount' => $orders[0]->total_price,
            'status' => 'success',
            'qris_url' => 'https://midtrans.com/qris/example',
            'transaction_time' => now()->subMinutes(10),
            'settlement_time' => now()->subMinutes(5),
            'refund_proof' => null,
            'refund_reason' => null,
            'refund_time' => null,
        ]);

        // 2️⃣ PENDING PAYMENT
        Payment::create([
            'order_id' => $orders[1]->id,
            'payment_method' => 'qris',
            'amount' => $orders[1]->total_price,
            'status' => 'pending',
            'qris_url' => 'https://midtrans.com/qris/example',
            'transaction_time' => now()->subMinutes(2),
            'settlement_time' => null,
            'refund_proof' => null,
            'refund_reason' => null,
            'refund_time' => null,
        ]);

        // 3️⃣ REFUNDED PAYMENT
        Payment::create([
            'order_id' => $orders[2]->id,
            'payment_method' => 'qris',
            'amount' => $orders[2]->total_price,
            'status' => 'refunded',
            'qris_url' => 'https://midtrans.com/qris/example',
            'transaction_time' => now()->subHours(1),
            'settlement_time' => now()->subMinutes(50),
            'refund_proof' => 'refunds/bukti-transfer.jpg',
            'refund_reason' => 'Pesanan dibatalkan',
            'refund_time' => now()->subMinutes(30),
        ]);
    }
}
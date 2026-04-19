<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            $status = 'pending';

            if ($order->payment_status === 'paid') {
                $status = 'success';
            }

            // 🔥 tambahan logic refund
            if ($order->order_status === 'cancelled' && $order->payment_status === 'paid') {
                $status = 'refunded';
            }
            // Tentukan method (biar variatif)
            $method = fake()->randomElement(['qris', 'cash']);

            // Mapping dari order -> payment
            if ($order->payment_status === 'paid') {

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $method,
                    'amount' => $order->total_price,
                    'status' => 'success',
                    'qris_url' => $method === 'qris' ? 'https://midtrans.com/qris/example' : null,
                    'transaction_time' => now()->subMinutes(rand(10, 120)),
                    'settlement_time' => now()->subMinutes(rand(1, 9)),
                    'refund_proof' => null,
                    'refund_reason' => null,
                    'refund_time' => null,
                ]);

            } else {

                // unpaid → bisa pending / expired / failed
                $status = fake()->randomElement(['pending', 'expired', 'failed']);

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $method,
                    'amount' => $order->total_price,
                    'status' => $status,
                    'qris_url' => $method === 'qris' ? 'https://midtrans.com/qris/example' : null,
                    'transaction_time' => now()->subMinutes(rand(1, 10)),
                    'settlement_time' => null,
                    'refund_proof' => null,
                    'refund_reason' => null,
                    'refund_time' => null,
                ]);
            }
        }
    }
}
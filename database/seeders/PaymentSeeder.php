<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $order = Order::first();

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'qris',
            'midtrans_order_id' => 'MID-' . $order->order_number,
            'amount' => $order->total_price,
            'status' => 'success',
            'qris_url' => 'https://midtrans.com/qris/example',
            'transaction_time' => now(),
            'settlement_time' => now(),
        ]);
    }
}

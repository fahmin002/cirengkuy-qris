<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach (range(1, 10) as $i) {

            // 🎲 tentukan payment status dulu
            $paymentStatus = fake()->randomElement(['success', 'pending', 'expired', 'failed']);

            // 🧠 mapping ke order
            $orderPaymentStatus = $paymentStatus === 'success' ? 'paid' : 'unpaid';

            $orderStatus = match ($paymentStatus) {
                'success' => 'processing',
                'pending' => 'pending',
                'expired' => 'pending',
                'failed' => 'cancelled',
            };

            // 🧾 CREATE ORDER
            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . $i,
                'user_id' => 1,
                'total_price' => 0,
                'delivery_type' => fake()->randomElement(['delivery', 'pickup']),
                'order_type' => fake()->randomElement(['cooked', 'frozen']),
                'order_status' => $orderStatus,
                'payment_status' => $orderPaymentStatus,
                'recipient_name' => fake()->name(),
                'recipient_phone' => fake()->phoneNumber(),
                'delivery_address' => fake()->address(),
                'delivery_notes' => fake()->sentence(),
            ]);

            $total = 0;

            // 🛒 items
            foreach ($products->random(rand(1, 3)) as $product) {

                $qty = rand(1, 3);
                $subtotal = $product->price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            // update total
            $order->update([
                'total_price' => $total
            ]);

            // 💳 CREATE PAYMENT (WAJIB ADA)
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'qris',
                'amount' => $total,
                'status' => $paymentStatus,
                'qris_url' => 'https://midtrans.com/qris/example',
                'transaction_time' => now()->subMinutes(rand(1, 30)),
                'settlement_time' => $paymentStatus === 'success' ? now() : null,
                'refund_proof' => null,
                'refund_reason' => null,
                'refund_time' => null,
            ]);
        }
    }
}
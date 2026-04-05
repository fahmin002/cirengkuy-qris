<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {

            $total = 0;

            // tiap order punya 1–3 item
            foreach ($products->random(rand(1, 3)) as $product) {

                $qty = rand(1, 3);
                $subtotal = $product->price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                    'subtotal' => $subtotal, // ⬅️ penting!
                ]);

                $total += $subtotal;
            }

            // update total di orders
            $order->update([
                'total_price' => $total
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        for ($i = 1; $i <= 10; $i++) {

            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . $i,
                'user_id' => 1,
                'total_price' => 0, // nanti dihitung dari items
                'delivery_type' => fake()->randomElement(['delivery', 'pickup']),
                'order_type' => fake()->randomElement(['cooked', 'frozen']),
                'order_status' => fake()->randomElement(['pending', 'processing', 'completed']),
                'payment_status' => fake()->randomElement(['paid', 'unpaid']),
                'recipient_name' => fake()->name(),
                'recipient_phone' => fake()->phoneNumber(),
                'delivery_address' => fake()->address(),
                'delivery_notes' => fake()->sentence(),
            ]);

            $total = 0;

            // setiap order punya 1-3 item
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

            // update total price
            $order->update([
                'total_price' => $total
            ]);
        }
    }
}
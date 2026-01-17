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
        $order = Order::first();

        $product1 = Product::where('name', 'Cireng Ayam Original')->first();
        $product2 = Product::where('name', 'Cireng Ayam Pedas')->first();

        OrderItem::insert([
            [
                'order_id' => $order->id,
                'product_id' => $product1->id,
                'quantity' => 2,
                'price' => $product1->price,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => $order->id,
                'product_id' => $product2->id,
                'quantity' => 1,
                'price' => $product2->price,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

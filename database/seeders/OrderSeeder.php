<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::create([
            'order_number' => 'ORD-' . now()->format('YmdHis'),
            'user_id' => 1,
            'total_price' => 38000,
            'delivery_type' => 'delivery',
            'status' => 'paid',
            'recipient_name' => 'Budi Santoso',
            'recipient_phone' => '08123456789',
            'delivery_address' => 'Jl. Cireng Enak No. 12',
            'delivery_notes' => 'Rumah pagar hitam',
        ]);
    }
}

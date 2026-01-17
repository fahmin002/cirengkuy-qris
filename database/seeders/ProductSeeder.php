<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Cireng Ayam Original',
                'description' => 'Cireng isi ayam original gurih',
                'price' => 12000,
                'stock' => 100,
                'is_active' => true,
                'image' => 'products/cireng-original.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cireng Ayam Pedas',
                'description' => 'Cireng isi ayam pedas nampol',
                'price' => 14000,
                'stock' => 80,
                'is_active' => true,
                'image' => 'products/cireng-pedas.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

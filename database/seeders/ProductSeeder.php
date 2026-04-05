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
            [
                'name' => 'Cireng Keju Lumer',
                'description' => 'Isi keju mozzarella yang meleleh',
                'price' => 15000,
                'stock' => 70,
                'is_active' => true,
                'image' => 'products/cireng-keju.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cireng Sosis BBQ',
                'description' => 'Isi sosis dengan saus BBQ manis gurih',
                'price' => 16000,
                'stock' => 60,
                'is_active' => true,
                'image' => 'products/cireng-bbq.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cireng Frozen Pack (10 pcs)',
                'description' => 'Cireng frozen siap goreng di rumah',
                'price' => 30000,
                'stock' => 50,
                'is_active' => true,
                'image' => 'products/cireng-frozen.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cireng Mix Box',
                'description' => 'Campuran berbagai varian cireng',
                'price' => 35000,
                'stock' => 40,
                'is_active' => true,
                'image' => 'products/cireng-mix.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
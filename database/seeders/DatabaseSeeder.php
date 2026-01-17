<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'PB Admin',
        //     'email' => 'admin@indo.com',
        //     'password' => bcrypt('admin123'),
        //     'role' => 'admin',
        // ]);
        if (!User::where('email', 'admin@indo.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@indo.com',
                'password' => Hash::make('admin123'), // ubah sesuai keinginan
                'role' => 'admin',
            ]);
        }

        $this->call([
            ProductSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class
        ]);

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔥 Matikan foreign key check dulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 🔥 Truncate semua tabel, urut dari child → parent
        DB::table('shipments')->truncate();
        DB::table('payments')->truncate();
        DB::table('order_details')->truncate();
        DB::table('orders')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();

        // 🔥 Nyalakan lagi foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🌱 Jalankan semua seeder
        $this->call([
            UsersSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
            OrdersSeeder::class,
            OrderDetailsSeeder::class,
            PaymentsSeeder::class,
            ShipmentsSeeder::class,
        ]);
    }
}

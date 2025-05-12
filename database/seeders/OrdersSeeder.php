<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                DB::table('orders')->insert([
            ['users_id' => 1, 'orders_date' => now()->subDays(9), 'orders_total_price' => 85000, 'orders_status' => 'Pending', 'created_at' => now(), 'status_del' => false],
            ['users_id' => 2, 'orders_date' => now()->subDays(8), 'orders_total_price' => 95000, 'orders_status' => 'Processing', 'created_at' => now(), 'status_del' => false],
            ['users_id' => 3, 'orders_date' => now()->subDays(7), 'orders_total_price' => 105000, 'orders_status' => 'Shipped', 'created_at' => now(), 'status_del' => false],
            ['users_id' => 4, 'orders_date' => now()->subDays(6), 'orders_total_price' => 65000, 'orders_status' => 'Delivered', 'created_at' => now(), 'status_del' => false],
            ['users_id' => 5, 'orders_date' => now()->subDays(5), 'orders_total_price' => 78000, 'orders_status' => 'Cancelled', 'created_at' => now(), 'status_del' => false],
            ['users_id' => 6, 'orders_date' => now()->subDays(4), 'orders_total_price' => 96000, 'orders_status' => 'Pending', 'created_at' => now(), 'status_del' => false],
            ['users_id' => 7, 'orders_date' => now()->subDays(3), 'orders_total_price' => 88000, 'orders_status' => 'Processing', 'created_at' => now(), 'status_del' => false],
            ['users_id' => 8, 'orders_date' => now()->subDays(2), 'orders_total_price' => 92000, 'orders_status' => 'Shipped', 'created_at' => now(), 'status_del' => false],
            ['users_id' => 9, 'orders_date' => now()->subDay(), 'orders_total_price' => 100000, 'orders_status' => 'Delivered', 'created_at' => now(), 'status_del' => false],
            ['users_id' => 10, 'orders_date' => now(), 'orders_total_price' => 73000, 'orders_status' => 'Pending', 'created_at' => now(), 'status_del' => false],
        ]);
        
    }
}

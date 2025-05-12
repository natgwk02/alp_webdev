<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_details')->insert([
            ['products_id' => 1, 'order_details_quantity' => 2, 'created_at' => now(), 'status_del' => false],
            ['products_id' => 2, 'order_details_quantity' => 1, 'created_at' => now(), 'status_del' => false],
            ['products_id' => 3, 'order_details_quantity' => 3, 'created_at' => now(), 'status_del' => false],
            ['products_id' => 4, 'order_details_quantity' => 1, 'created_at' => now(), 'status_del' => false],
            ['products_id' => 5, 'order_details_quantity' => 4, 'created_at' => now(), 'status_del' => false],
            ['products_id' => 6, 'order_details_quantity' => 2, 'created_at' => now(), 'status_del' => false],
            ['products_id' => 7, 'order_details_quantity' => 1, 'created_at' => now(), 'status_del' => false],
            ['products_id' => 8, 'order_details_quantity' => 3, 'created_at' => now(), 'status_del' => false],
            ['products_id' => 9, 'order_details_quantity' => 2, 'created_at' => now(), 'status_del' => false],
            ['products_id' => 10, 'order_details_quantity' => 1, 'created_at' => now(), 'status_del' => false],
        ]);
        
    }
}

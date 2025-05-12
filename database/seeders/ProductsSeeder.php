<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['categories_id' => 1, 'products_name' => 'Chicken Nugget', 'products_description' => 'Nugget ayam beku', 'products_stock' => 100, 'products_image' => 'nugget.jpg', 'unit_price' => 40000, 'orders_price' => 45000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_id' => 2, 'products_name' => 'Frozen Shrimp', 'products_description' => 'Udang beku siap masak', 'products_stock' => 80, 'products_image' => 'shrimp.jpg', 'unit_price' => 60000, 'orders_price' => 65000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_id' => 3, 'products_name' => 'Ice Cream Mochi', 'products_description' => 'Mochi isi es krim', 'products_stock' => 50, 'products_image' => 'mochi.jpg', 'unit_price' => 25000, 'orders_price' => 30000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_id' => 4, 'products_name' => 'Banana Cake', 'products_description' => 'Kue pisang lembut', 'products_stock' => 60, 'products_image' => 'banana_cake.jpg', 'unit_price' => 20000, 'orders_price' => 22000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_id' => 5, 'products_name' => 'Cold Brew Coffee', 'products_description' => 'Kopi dingin kemasan', 'products_stock' => 150, 'products_image' => 'coldbrew.jpg', 'unit_price' => 18000, 'orders_price' => 20000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_id' => 6, 'products_name' => 'Frozen Pizza', 'products_description' => 'Pizza siap panggang', 'products_stock' => 40, 'products_image' => 'pizza.jpg', 'unit_price' => 50000, 'orders_price' => 55000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_id' => 7, 'products_name' => 'Beef Patty', 'products_description' => 'Patty sapi beku', 'products_stock' => 70, 'products_image' => 'beefpatty.jpg', 'unit_price' => 45000, 'orders_price' => 48000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_id' => 8, 'products_name' => 'Vegan Sausage', 'products_description' => 'Sosis vegan sehat', 'products_stock' => 30, 'products_image' => 'vegan.jpg', 'unit_price' => 35000, 'orders_price' => 37000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_id' => 9, 'products_name' => 'Potato Wedges', 'products_description' => 'Kentang beku siap goreng', 'products_stock' => 90, 'products_image' => 'wedges.jpg', 'unit_price' => 20000, 'orders_price' => 22000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_id' => 10, 'products_name' => 'Bolognese Sauce', 'products_description' => 'Saus pasta bolognese', 'products_stock' => 100, 'products_image' => 'sauce.jpg', 'unit_price' => 18000, 'orders_price' => 20000, 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
        ]);
        
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['categories_name' => 'Frozen Food', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Seafood', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Desserts', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Bakery', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Beverages', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Ready to Cook', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Meat', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Vegetarian', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Snacks', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Sauces', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
        ]);
        
    }
}

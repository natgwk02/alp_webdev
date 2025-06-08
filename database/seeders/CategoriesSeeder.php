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
        DB::table('categories')->delete();
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');

        // Insert 8 kategori baru
        DB::table('categories')->insert([
            ['categories_name' => 'Ready Meals',        'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Frozen Vegetable',   'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Frozen Dimsum',      'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Frozen Meat',        'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Frozen Nugget',      'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Frozen Fruit',       'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Frozen Seafood',     'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['categories_name' => 'Dessert',            'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['users_name' => 'Alice Smith', 'users_email' => 'alice@mail.com', 'users_password' => bcrypt('alice'), 'users_phone' => '081234567001', 'users_address' => 'Jl. Mawar 1', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['users_name' => 'Bob Johnson', 'users_email' => 'bob@mail.com', 'users_password' => bcrypt('123456'), 'users_phone' => '081234567002', 'users_address' => 'Jl. Melati 2', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['users_name' => 'Cindy Lee', 'users_email' => 'cindy@mail.com', 'users_password' => bcrypt('123456'), 'users_phone' => '081234567003', 'users_address' => 'Jl. Kenanga 3', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['users_name' => 'David Kim', 'users_email' => 'david@mail.com', 'users_password' => bcrypt('123456'), 'users_phone' => '081234567004', 'users_address' => 'Jl. Anggrek 4', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['users_name' => 'Eva Wong', 'users_email' => 'eva@mail.com', 'users_password' => bcrypt('123456'), 'users_phone' => '081234567005', 'users_address' => 'Jl. Dahlia 5', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['users_name' => 'Fikri Akbar', 'users_email' => 'fikri@mail.com', 'users_password' => bcrypt('123456'), 'users_phone' => '081234567006', 'users_address' => 'Jl. Cemara 6', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['users_name' => 'Gita Ayu', 'users_email' => 'gita@mail.com', 'users_password' => bcrypt('123456'), 'users_phone' => '081234567007', 'users_address' => 'Jl. Flamboyan 7', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['users_name' => 'Hendra Wijaya', 'users_email' => 'hendra@mail.com', 'users_password' => bcrypt('123456'), 'users_phone' => '081234567008', 'users_address' => 'Jl. Sakura 8', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['users_name' => 'Ika Lestari', 'users_email' => 'ika@mail.com', 'users_password' => bcrypt('123456'), 'users_phone' => '081234567009', 'users_address' => 'Jl. Teratai 9', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
            ['users_name' => 'Joko Santoso', 'users_email' => 'joko@mail.com', 'users_password' => bcrypt('123456'), 'users_phone' => '081234567010', 'users_address' => 'Jl. Bougenville 10', 'created_at' => now(), 'updated_at' => now(), 'status_del' => false],
        ]);
    }
}

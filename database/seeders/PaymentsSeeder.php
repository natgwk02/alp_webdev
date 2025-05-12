<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payments')->insert([
            ['orders_id' => 1, 'payments_method' => 1, 'payments_status' => 1, 'payments_date' => now()->subDays(9), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 2, 'payments_method' => 2, 'payments_status' => 1, 'payments_date' => now()->subDays(8), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 3, 'payments_method' => 3, 'payments_status' => 2, 'payments_date' => now()->subDays(7), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 4, 'payments_method' => 1, 'payments_status' => 1, 'payments_date' => now()->subDays(6), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 5, 'payments_method' => 2, 'payments_status' => 0, 'payments_date' => now()->subDays(5), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 6, 'payments_method' => 1, 'payments_status' => 1, 'payments_date' => now()->subDays(4), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 7, 'payments_method' => 3, 'payments_status' => 2, 'payments_date' => now()->subDays(3), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 8, 'payments_method' => 2, 'payments_status' => 1, 'payments_date' => now()->subDays(2), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 9, 'payments_method' => 1, 'payments_status' => 1, 'payments_date' => now()->subDay(), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 10, 'payments_method' => 2, 'payments_status' => 1, 'payments_date' => now(), 'created_at' => now(), 'status_del' => false],
        ]);
        
    }
}

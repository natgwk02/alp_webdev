<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shipments')->insert([
            ['orders_id' => 1, 'shipments_tracking_number' => 'CHILL0001', 'shipments_date' => now()->subDays(8), 'shipments_delivery_date' => now()->subDays(6), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 2, 'shipments_tracking_number' => 'CHILL0002', 'shipments_date' => now()->subDays(7), 'shipments_delivery_date' => now()->subDays(5), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 3, 'shipments_tracking_number' => 'CHILL0003', 'shipments_date' => now()->subDays(6), 'shipments_delivery_date' => now()->subDays(4), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 4, 'shipments_tracking_number' => 'CHILL0004', 'shipments_date' => now()->subDays(5), 'shipments_delivery_date' => now()->subDays(3), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 5, 'shipments_tracking_number' => 'CHILL0005', 'shipments_date' => now()->subDays(4), 'shipments_delivery_date' => now()->subDays(2), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 6, 'shipments_tracking_number' => 'CHILL0006', 'shipments_date' => now()->subDays(3), 'shipments_delivery_date' => now()->subDays(1), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 7, 'shipments_tracking_number' => 'CHILL0007', 'shipments_date' => now()->subDays(2), 'shipments_delivery_date' => now(), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 8, 'shipments_tracking_number' => 'CHILL0008', 'shipments_date' => now()->subDay(), 'shipments_delivery_date' => now()->addDay(), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 9, 'shipments_tracking_number' => 'CHILL0009', 'shipments_date' => now(), 'shipments_delivery_date' => now()->addDays(2), 'created_at' => now(), 'status_del' => false],
            ['orders_id' => 10, 'shipments_tracking_number' => 'CHILL0010', 'shipments_date' => now(), 'shipments_delivery_date' => now()->addDays(3), 'created_at' => now(), 'status_del' => false],
        ]);
        
    }
}

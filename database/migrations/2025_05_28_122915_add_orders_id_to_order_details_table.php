<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Add the foreign key column. It should match the primary key type of your 'orders' table.
            // Assuming 'orders_id' in 'orders' table is an unsignedBigInteger.
            $table->unsignedBigInteger('orders_id')->after('order_details_id'); // Or another suitable position

            // Define the foreign key constraint
            $table->foreign('orders_id')
                  ->references('orders_id')->on('orders') // Assumes PK of orders table is 'orders_id'
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['orders_id']);
            $table->dropColumn('orders_id');
        });
    }
};

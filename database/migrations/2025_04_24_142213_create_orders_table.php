<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        

        Schema::create('orders', function (Blueprint $table) {
            $table->id('orders_id');
            $table->unsignedBigInteger('users_id');
            $table->date('orders_date');
            $table->integer('orders_total_price');
            $table->string('orders_status', 10);
            $table->timestamps();
            $table->boolean('status_del')->default(false);
 
            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

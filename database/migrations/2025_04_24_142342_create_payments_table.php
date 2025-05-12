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
       

        Schema::create('payments', function (Blueprint $table) {
            $table->id('payments_id');
            $table->unsignedBigInteger('orders_id');
            $table->integer('payments_method');
            $table->integer('payments_status');
            $table->date('payments_date');
            $table->timestamp('created_at')->useCurrent();
            $table->boolean('status_del')->default(false);

            $table->foreign('orders_id')->references('orders_id')->on('orders')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

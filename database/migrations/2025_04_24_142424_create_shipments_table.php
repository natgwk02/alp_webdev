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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id('shipments_id');
            $table->unsignedBigInteger('orders_id');
            $table->string('shipments_tracking_number');
            $table->date('shipments_date');
            $table->date('shipments_delivery_date');
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
        Schema::dropIfExists('shipments');
    }
};

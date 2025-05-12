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

        Schema::create('products', function (Blueprint $table) {
            $table->id('products_id');
            $table->unsignedBigInteger('categories_id');
            $table->string('products_name', 50);
            $table->string('products_description', 50);
            $table->integer('products_stock');
            $table->string('products_image', 50);
            $table->integer('unit_price');
            $table->integer('orders_price');
            $table->timestamps();
            $table->boolean('status_del')->default(false);

            $table->foreign('categories_id')->references('categories_id')->on('categories')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }

    
};

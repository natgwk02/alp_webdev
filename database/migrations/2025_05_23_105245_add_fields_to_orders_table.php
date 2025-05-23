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
        Schema::table('orders', function (Blueprint $table) {
    $table->string('first_name')->nullable();
    $table->string('last_name')->nullable();
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->string('address')->nullable();
    $table->string('city')->nullable();
    $table->string('zip')->nullable();
    $table->string('country')->nullable();
    $table->string('payment_method')->nullable();
    $table->string('payment_status')->default('Unpaid');
    $table->integer('subtotal')->default(0);
    $table->integer('shipping_fee')->default(0);
    $table->integer('tax')->default(0);
    $table->integer('voucher_discount')->default(0);
    $table->integer('total')->default(0);
    $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};

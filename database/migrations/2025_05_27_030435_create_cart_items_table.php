<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();  // Menambahkan primary key auto increment
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');  // Menghubungkan ke tabel carts
            $table->foreignId('products_id')->constrained()->onDelete('cascade');  // Menghubungkan ke tabel products
            $table->integer('quantity')->default(1);  // Menyimpan jumlah produk
            $table->timestamps();  // Menyimpan waktu pembuatan dan pembaruan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');  // Menghapus tabel cart_items
    }
};

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
        // Menambahkan kolom users_id di tabel carts
        // Schema::table('carts', function (Blueprint $table) {
        //     $table->unsignedBigInteger('users_id')->nullable(); // Menambahkan kolom users_id
        //     $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade'); // Menambahkan foreign key
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom users_id dari tabel carts
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['users_id']);
            $table->dropColumn('users_id');
        });
    }
};

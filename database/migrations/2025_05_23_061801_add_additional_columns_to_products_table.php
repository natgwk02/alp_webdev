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
        Schema::table('products', function (Blueprint $table) {
        // $table->string('hover_image')->nullable()->after('products_image');
        // $table->float('rating')->nullable()->after('orders_price');
        // $table->integer('calories')->nullable()->after('rating');        
        // $table->string('protein', 20)->nullable()->after('calories');     
        // $table->string('fat', 20)->nullable()->after('protein');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //  $table->dropColumn(['hover_image', 'rating', 'calories','protein','fat']);
        });
    }
};

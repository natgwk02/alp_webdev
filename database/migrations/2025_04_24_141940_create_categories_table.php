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
      

        Schema::create('categories', function (Blueprint $table) {
            $table->id('categories_id');
            $table->string('categories_name', 50);
            $table->timestamps();
            $table->boolean('status_del')->default(false);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

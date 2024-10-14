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
        Schema::create('products_has_characteristics', function (Blueprint $table) {
            $table->primary(['product_id', 'characteristic_id']);
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('characteristic_id');
            $table->foreign('characteristic_id')->references('id')->on('characteristics')->onDelete('cascade');
            $table->string('value', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_has_characteristics');
    }
};

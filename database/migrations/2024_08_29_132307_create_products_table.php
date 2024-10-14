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
            $table->id();
            $table->string('name', 100);
            $table->string('sku', 100)->unique();
            $table->string('code', 100)->unique();
            $table->string('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('cost', 12, 2);
            $table->unsignedInteger('minimum_stock')->default(1);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedBigInteger('alert_stock_id');
            $table->foreign('alert_stock_id')->references('id')->on('status_types')->unsigned()->onDelete('restrict');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status_types')->onDelete('restrict');
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('restrict');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('restrict');
            $table->unsignedBigInteger('measurement_unit_id');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units')->onDelete('restrict');
            $table->timestamps();
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

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
        Schema::create('model_cars', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->year("year");
            $table->foreignId('brand_id')->constrained('brands')->onDelete('restrict');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status_types')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_cars');
    }
};

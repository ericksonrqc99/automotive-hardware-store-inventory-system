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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Longitud predeterminada
            $table->string('contact', 100); // Contacto del proveedor

            $table->unsignedMediumInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->unsignedMediumInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('restrict');
            $table->unsignedMediumInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('restrict');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status_types')->onDelete('restrict');
            $table->string('address', 255)->nullable(); // Dirección, puede ser más específica
            $table->string('phone', 20); // Número de teléfono con prefijo internacional
            $table->string('email', 255)->nullable(); // Correo electrónico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};

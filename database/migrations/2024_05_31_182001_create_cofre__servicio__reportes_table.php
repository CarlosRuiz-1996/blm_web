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
        Schema::create('cofre_servicio_reportes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruta_servicios_id'); // Definir la columna primero
            $table->foreign('ruta_servicios_id')->references('id')->on('ruta_servicios')->onDelete('cascade');

            $table->unsignedBigInteger('cofre_llaves_id'); // Definir la columna primero
            $table->foreign('cofre_llaves_id')->references('id')->on('cofre_llaves')->onDelete('cascade');

            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cofre__servicio__reportes');
    }
};

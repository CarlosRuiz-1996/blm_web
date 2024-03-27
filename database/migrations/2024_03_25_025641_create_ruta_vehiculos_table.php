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
        // tabla para llevar la relacion de vehiculos con rutas
        Schema::create('ruta_vehiculos', function (Blueprint $table) {
            $table->id();
            //id de la ruta
            $table->unsignedBigInteger('ruta_id');
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
            //id de la ruta
            $table->unsignedBigInteger('ctg_vehiculo_id');
            $table->foreign('ctg_vehiculo_id')->references('id')->on('ctg_vehiculos')->onDelete('cascade');
            $table->integer('status_ruta_vehiculos')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_vehiculos');
    }
};

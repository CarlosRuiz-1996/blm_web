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
        Schema::create('ruta_vehiculo_reportes', function (Blueprint $table) {
            $table->id();
            //vehiculos
            $table->unsignedBigInteger('ctg_vehiculo_id');
            $table->foreign('ctg_vehiculo_id')->references('id')->on('ctg_vehiculos')->onDelete('cascade');
            //rutas
            $table->unsignedBigInteger('ruta_id');
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
            $table->integer('status_ruta_vehiculo_reportes')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_vehiculo_reportes');
    }
};

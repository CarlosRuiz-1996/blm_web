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
        Schema::create('servicios_evidencias_recolecta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruta_servicios_id');
            $table->integer('status_evidencia_recolecta');
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('ruta_servicios_id')->references('id')->on('ruta_servicios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios_evidencias_recolecta');
    }
};

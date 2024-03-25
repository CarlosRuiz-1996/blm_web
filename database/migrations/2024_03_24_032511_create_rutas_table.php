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
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->time('hora_inicio');
            $table->time('hora_fin');
            //nombre de la ruta            
            $table->unsignedBigInteger('ctg_rutas_id');
            $table->foreign('ctg_rutas_id')->references('id')->on('ctg_rutas')->onDelete('cascade');
            //id del dia de la semana
            $table->unsignedBigInteger('ctg_ruta_dia_id');
            $table->foreign('ctg_ruta_dia_id')->references('id')->on('ctg_ruta_dias')->onDelete('cascade');
            //riesgos de la ruta            
            $table->unsignedBigInteger('ctg_rutas_riesgo_id');
            $table->foreign('ctg_rutas_riesgo_id')->references('id')->on('ctg_rutas_riesgos')->onDelete('cascade');
            //estados de la ruta            
            $table->unsignedBigInteger('ctg_rutas_estado_id');
            $table->foreign('ctg_rutas_estado_id')->references('id')->on('ctg_rutas_estados')->onDelete('cascade');
            $table->integer('status_ruta')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};

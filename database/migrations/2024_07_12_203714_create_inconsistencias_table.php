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
        Schema::create('inconsistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
    
            $table->unsignedBigInteger('ruta_servicio_reportes_id');
            $table->foreign('ruta_servicio_reportes_id')->references('id')->on('ruta_servicio_reportes');
            
            $table->date('fecha_comprobante'); //fecha de la ruta ?
            $table->string('folio'); //papeleta
            $table->float('importe_indicado');
            $table->float('importe_comprobado');
            $table->float('diferencia');
            $table->integer('tipo'); //1-faltante/2-sobrante
            $table->text('observacion'); //1-faltante/2-sobrante

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inconsistencias');
    }
};

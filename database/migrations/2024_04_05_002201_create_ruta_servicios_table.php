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
        Schema::create('ruta_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->unsignedBigInteger('ruta_id');
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
            $table->float('monto')->nullable();
            $table->string('folio')->nullable();
            $table->integer('envases')->nullable();
            $table->integer('tipo_servicio')->default(1);
            $table->integer('status_ruta_servicios')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_servicios');
    }
};

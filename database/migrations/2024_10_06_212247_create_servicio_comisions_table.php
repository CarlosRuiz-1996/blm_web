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
        Schema::create('servicio_comisions', function (Blueprint $table) {
            $table->id();
            $table->string('papeleta');
            $table->float('monto');
            $table->unsignedBigInteger('ruta_servicio_id');
            $table->foreign('ruta_servicio_id')->references('id')->on('ruta_servicios')->cascadeOnDelete()->cascadeOnUpdate();  
            $table->integer('status_servicio_comisions')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_comisions');
    }
};

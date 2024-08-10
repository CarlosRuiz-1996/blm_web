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
        
        Schema::create('bancos_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->float('monto')->default(0);
            $table->string('papeleta')->nullable();
            $table->date('fecha_entrega');
            $table->integer('tipo_servicio')->default(1);
            $table->integer('status_bancos_servicios')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos_servicios');
    }
};

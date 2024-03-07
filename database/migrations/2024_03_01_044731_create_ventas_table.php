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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            //usuario logueado, quien hizo la venta
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //cotizacion que se realizo
            $table->unsignedBigInteger('cotizacion_id')->nullable();
            $table->foreign('cotizacion_id')->references('id')->on('cotizacion')->onDelete('cascade');
            //servicio por si no pasa por cotizacion
            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->integer('status_ventas')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};

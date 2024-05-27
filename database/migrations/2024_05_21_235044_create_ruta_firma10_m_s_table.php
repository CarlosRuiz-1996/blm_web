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
        Schema::create('ruta_firma10_m_s', function (Blueprint $table) {
            $table->id();
            //ruta que supera los 10 m
            $table->unsignedBigInteger('ruta_id');
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
            //boveda
            $table->unsignedBigInteger('empleado_id_boveda')->nullable();//usuario
            $table->foreign('empleado_id_boveda')->references('id')->on('empleados')->onDelete('cascade');
            $table->integer('confirm_boveda')->nullable();
            //operaciones
            $table->unsignedBigInteger('empleado_id_operaciones')->nullable();//usuario
            $table->foreign('empleado_id_operaciones')->references('id')->on('empleados')->onDelete('cascade');
            $table->integer('confirm_operaciones')->nullable();

            //direccion
            $table->unsignedBigInteger('empleado_id_direccion')->nullable();//usuario
            $table->foreign('empleado_id_direccion')->references('id')->on('empleados')->onDelete('cascade');
            $table->integer('confirm_direccion')->nullable();

            $table->integer('status_ruta_firma10_m_s')->default(0);//0-en espera/negada 1-aceptada  2-finalizada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_firma10_m_s');
    }
};

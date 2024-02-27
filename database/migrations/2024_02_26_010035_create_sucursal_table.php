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
        Schema::create('sucursal', function (Blueprint $table) {
            $table->id();
            //usuario
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //cp
            $table->unsignedBigInteger('ctg_cp_id')->nullable();
            $table->foreign('ctg_cp_id')->references('id')->on('ctg_cp')->onDelete('cascade');
            // calle y numero
            $table->string('direccion')->nullable();
            $table->string('referencias')->nullable();
            $table->string('longitud')->nullable();
            $table->string('latitud')->nullable();

            $table->string('sucursal')->nullable();
            $table->string('contacto')->nullable();
            $table->string('cargo')->nullable();
            $table->string('correo')->nullable();
            $table->string('phone')->nullable();
            $table->dateTime('fecha_evaluacion')->nullable();
            $table->dateTime('fecha_inicio_servicio')->nullable();
            $table->integer('rpt_factibilidad_status')->default(0);
            $table->integer('status_sucursal')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursal');
    }
};

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
        Schema::create('sucursal_servicio', function (Blueprint $table) {
            $table->id();
             //sucursal_id
             $table->unsignedBigInteger('sucursal_id');
             $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('cascade');
             //servicio_id
             $table->unsignedBigInteger('servicio_id');
             $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
             $table->integer('status_sucursal_servicio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursal_servicio');
    }
};

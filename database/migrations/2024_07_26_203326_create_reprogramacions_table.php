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
        Schema::create('reprogramacions', function (Blueprint $table) {
            $table->id();
            $table->text('motivo');
            $table->text('evidencia');
            $table->unsignedBigInteger('ruta_servicio_id');
            $table->foreign('ruta_servicio_id')->references('id')->on('ruta_servicios')->onDelete('cascade');
            $table->integer('status_reprogramacions')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reprogramacions');
    }
};

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
        Schema::create('cambio_efectivo_denominaciones', function (Blueprint $table) {
            $table->id();
            $table->float('monto');

            //tipo de denominacion
            $table->unsignedBigInteger('ctg_denominacion_id');
            $table->foreign('ctg_denominacion_id')->references('id')->on('ctg_denominacions')->onDelete('cascade');
            //cambio efectivo
            $table->unsignedBigInteger('cambio_efectivo_id');
            $table->foreign('cambio_efectivo_id')->references('id')->on('cambio_efectivos')->onDelete('cascade');
            $table->integer('status_cambio_efectivo_denominaciones')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cambio_efectivo_denominaciones');
    }
};

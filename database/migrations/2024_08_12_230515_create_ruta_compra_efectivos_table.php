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
        
        Schema::create('ruta_compra_efectivos', function (Blueprint $table) {
            $table->id();
            //ruta
            $table->unsignedBigInteger('ruta_id');
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
            //compra
            $table->unsignedBigInteger('compra_efectivo_id');
            $table->foreign('compra_efectivo_id')->references('id')->on('compra_efectivos')->onDelete('cascade');
            //datos extras
            $table->integer('status_ruta_compra_efectivos')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_compra_efectivos');
    }
};

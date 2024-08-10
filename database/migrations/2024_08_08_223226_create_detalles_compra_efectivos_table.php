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
        

        Schema::create('detalles_compra_efectivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->float('monto');
            $table->unsignedBigInteger('compra_efectivo_id');
            $table->foreign('compra_efectivo_id')->references('id')->on('compra_efectivos')->onDelete('cascade');
            $table->integer('status_detalles_compra_efectivos')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_compra_efectivos');
    }
};

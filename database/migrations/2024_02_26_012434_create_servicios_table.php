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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->float('precio_unitario')->default(0);
            $table->integer('cantidad');
            $table->float('subtotal');
            $table->unsignedBigInteger('ctg_precio_servicio_id')->nullable();
            $table->foreign('ctg_precio_servicio_id')->references('id')->on('ctg_precio_servicio')->onDelete('cascade');
            $table->unsignedBigInteger('ctg_servicios_id');
            $table->foreign('ctg_servicios_id')->references('id')->on('ctg_servicios')->onDelete('cascade');
            // $table->unsignedBigInteger('cotizacion_id');
            // $table->foreign('cotizacion_id')->references('id')->on('cotizacion')->onDelete('cascade');
            $table->integer('servicio_especial')->default(0);
            $table->integer('status_servicio')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};

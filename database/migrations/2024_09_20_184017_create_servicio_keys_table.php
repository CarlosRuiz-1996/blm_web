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
        Schema::create('servicio_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruta_servicio_id');
            $table->foreign('ruta_servicio_id')->references('id')->on('ruta_servicios')->cascadeOnDelete()->cascadeOnUpdate();  
            $table->string('key');
            $table->integer('status_servicio_keys')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_keys');
    }
};

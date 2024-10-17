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
        Schema::create('ruta_servicio_transferirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_old')->constrained('rutas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('ruta_new')->constrained('rutas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_servicio_transferirs');
    }
};

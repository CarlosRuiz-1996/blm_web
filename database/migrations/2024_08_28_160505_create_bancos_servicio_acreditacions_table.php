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
        Schema::create('bancos_servicio_acreditacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servicios_envases_ruta_id');
            $table->foreign('servicios_envases_ruta_id')->references('id')->on('servicios_envases_rutas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('folio')->nullable();
            $table->integer('status_acreditacion')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos_servicio_acreditacions');
    }
};

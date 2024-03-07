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
        Schema::create('memoranda', function (Blueprint $table) {
            $table->id();
            $table->string('grupo');
            //tipo solicitud
            $table->unsignedBigInteger('ctg_tipo_solicitud_id')->nullable();
            $table->foreign('ctg_tipo_solicitud_id')->references('id')->on('ctg_tipo_solicitud')->onDelete('cascade');
            //tipo d servicio
            $table->unsignedBigInteger('ctg_tipo_servicio_id')->nullable();
            $table->foreign('ctg_tipo_servicio_id')->references('id')->on('ctg_tipo_servicio')->onDelete('cascade');
            //
            $table->unsignedBigInteger('cotizacion_id')->nullable();
            $table->foreign('cotizacion_id')->references('id')->on('cotizacion_id')->onDelete('cascade');

            $table->string('observaciones');
            $table->integer('status_memoranda')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memoranda');
    }
};

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
        Schema::create('memorandum_servicios', function (Blueprint $table) {
            $table->id();
            //tipo sucursal
            $table->unsignedBigInteger('sucursal_servicio_id')->nullable();
            $table->foreign('sucursal_servicio_id')->references('id')->on('sucursal_servicio')->onDelete('cascade');
            //tipo memoranda
            $table->unsignedBigInteger('memoranda_id')->nullable();
            $table->foreign('memoranda_id')->references('id')->on('memoranda')->onDelete('cascade');
            //tipo ctg_dia_servicio
            $table->unsignedBigInteger('ctg_dia_servicio_id')->nullable();
            $table->foreign('ctg_dia_servicio_id')->references('id')->on('ctg_dia_servicio')->onDelete('cascade');
            //tipo ctg_dia_entrega_id
            $table->unsignedBigInteger('ctg_dia_entrega_id')->nullable();
            $table->foreign('ctg_dia_entrega_id')->references('id')->on('ctg_dia_entrega')->onDelete('cascade');
            //tipo ctg_horario_servicio
            $table->unsignedBigInteger('ctg_horario_servicio_id')->nullable();
            $table->foreign('ctg_horario_servicio_id')->references('id')->on('ctg_horario_servicio')->onDelete('cascade');
            //tipo ctg_horario_entrega
            $table->unsignedBigInteger('ctg_horario_entrega_id')->nullable();
            $table->foreign('ctg_horario_entrega_id')->references('id')->on('ctg_horario_entrega')->onDelete('cascade');
            //tipo ctg_consignatario
            $table->unsignedBigInteger('ctg_consignatario_id')->nullable();
            $table->foreign('ctg_consignatario_id')->references('id')->on('ctg_consignatario')->onDelete('cascade');

            $table->integer('status_sucursal_entregas')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorandum_servicios');
    }
};

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
        Schema::create('resguardo_resporte', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servicio_id'); // Definir la columna primero
            $table->decimal('resguardo_actual', 10, 2);
            $table->decimal('cantidad', 10, 2);
            $table->integer('tipo_servicio')->default(1);
            $table->string('status_reporte_resguardo');
            $table->timestamps();

            // Luego definir la clave foránea
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resguardo_resporte');
    }
};

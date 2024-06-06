<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('servicios_envases_rutas', function (Blueprint $table) {
            $table->id(); // ID autoincremental (BIGINT UNSIGNED)
            $table->unsignedBigInteger('ruta_servicios_id'); // ID de la ruta de servicios
            $table->integer('tipo_servicio'); // Tipo de servicio (por ejemplo, "Entrega", "Recolección", etc.)
            $table->integer('cantidad'); // Cantidad de servicios
            $table->string('folio'); // Folio relacionado al servicio
            $table->timestamps(); // Campos created_at y updated_at
            $table->string('status_envases'); // Estado de los envases (por ejemplo, "Lleno", "Vacío", etc.)

            // Definición de la llave foránea hacia la tabla de rutas de servicios
            $table->foreign('ruta_servicios_id')
                ->references('id')
                ->on('ruta_servicios')
                ->onDelete('cascade'); // Opcional: si se elimina la ruta de servicios, también se eliminan los servicios asociados
        });
    }
    public function down()
    {
        Schema::dropIfExists('servicios_envases_rutas');
    }
    
};

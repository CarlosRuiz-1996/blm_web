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
        Schema::create('factibilidad', function (Blueprint $table) {
            $table->id();
            //cliente-para quien es el reporte
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('cascade');
            //usuario-el id del evaluador de logueado de seguridad.
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //datos formulario
            $table->smallInteger('tiposervicio')->nullable();
            $table->string('otro_tiposervicio')->nullable();
            $table->smallInteger('comohacerservicio')->nullable();
            $table->smallInteger('horarioservicio')->nullable();
            $table->smallInteger('personalparaservicio')->nullable();
            $table->smallInteger('tipoconstruccion')->nullable();
            $table->string('otro_tipoconstruccion')->nullable();
            $table->smallInteger('nivelproteccionlugar')->nullable();
            $table->smallInteger('perimetro')->nullable();
            $table->smallInteger('peatonales')->nullable();
            $table->smallInteger('vehiculares')->nullable();
            $table->smallInteger('ctrlacesos')->nullable();
            $table->smallInteger('guardiaseg')->nullable();
            $table->string('otros_guardiaseg')->nullable();
            $table->smallInteger('armados')->nullable();
            $table->smallInteger('corporacion_armados')->nullable();
            $table->smallInteger('alarma')->nullable();
            $table->smallInteger('tiposenial')->nullable();
            $table->string('otros_tiposenial')->nullable();
            $table->smallInteger('tipoderespuesta')->nullable();
            $table->smallInteger('tipodefalla')->nullable();
            $table->smallInteger('camaras')->nullable();
            $table->smallInteger('cofre')->nullable();
            $table->text('descripcion_asalto')->nullable();
            $table->smallInteger('tipodezona')->nullable();
            $table->smallInteger('conviene')->nullable();

            $table->smallInteger('observaciones')->nullable();
           
            $table->integer('status_factibilidad')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factibilidad');
    }
};

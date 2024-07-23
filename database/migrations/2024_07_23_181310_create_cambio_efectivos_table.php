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
        Schema::create('cambio_efectivos', function (Blueprint $table) {
            $table->id();
            $table->float('monto');

            //empleado
            $table->unsignedBigInteger('empleado_boveda_id');//empleado de boveda
            $table->foreign('empleado_boveda_id')->references('id')->on('empleados')->onDelete('cascade');

            $table->string('from_change')->nullable();//aquien se le cambia
            $table->integer('status_cambio_efectivo')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cambio_efectivos');
    }
};

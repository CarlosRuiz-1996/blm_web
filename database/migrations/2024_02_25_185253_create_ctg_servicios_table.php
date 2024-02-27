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
        Schema::create('ctg_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('folio');
            $table->string('tipo');
            $table->string('descripcion');
            $table->string('unidad');
            $table->integer('status_servicio')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctg_servicios');
    }
};

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
        Schema::create('ctg_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion')->nullable();
            $table->string('serie')->nullable();
            $table->string('anio')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('placas')->nullable();
            $table->integer('status_ctg_vehiculos')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctg_vehiculos');
    }
};

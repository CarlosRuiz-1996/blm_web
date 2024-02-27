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
        Schema::create('ctg_precio_servicio', function (Blueprint $table) {
            $table->id();
            $table->string('concepto');
            $table->string('unidad');
            $table->float('precio');
            $table->string('vigencia');
            $table->integer('status_precio')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctg_precio_servicio');
    }
};

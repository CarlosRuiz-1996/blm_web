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
        Schema::create('servicio_concepto_foraneos', function (Blueprint $table) {
            $table->id();
            $table->string('concepto');
            $table->float('costo');
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_concepto_foraneos');
    }
};

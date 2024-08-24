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
      
        Schema::create('compra_efectivo_envases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalles_compra_efectivo_id');
            $table->foreign('detalles_compra_efectivo_id')->references('id')->on('detalles_compra_efectivos')->onDelete('cascade');   
            $table->float('monto')->default(0); 
            $table->string('papeleta')->nullable();
            $table->string('sello_seguridad')->nullable(); 
            $table->string('evidencia')->nullable(); 
            $table->integer('status_compra_efectivo_envases')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra_efectivo_envases');
    }
};

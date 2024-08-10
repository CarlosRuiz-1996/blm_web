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

        
        Schema::create('compra_efectivos', function (Blueprint $table) {
            $table->id();
        
            $table->float('total');
            $table->unsignedBigInteger('consignatario_id');
            $table->foreign('consignatario_id')->references('id')->on('ctg_consignatario')->onDelete('cascade');

            $table->date('fecha_compra');
            $table->integer('status_compra_efectivos')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra_efectivos');
    }
};

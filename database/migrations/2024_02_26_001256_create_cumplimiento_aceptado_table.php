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
        Schema::create('cumplimiento_aceptado', function (Blueprint $table) {
            $table->id();
            //cumplimiento
            $table->unsignedBigInteger('cumplimiento_id');
            $table->foreign('cumplimiento_id')->references('id')->on('cumplimiento')->onDelete('cascade');
            //ctg aceptado
            $table->unsignedBigInteger('ctg_aceptado_id');
            $table->foreign('ctg_aceptado_id')->references('id')->on('ctg_aceptado')->onDelete('cascade');
            $table->integer('status_cumplimiento_aceptado')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumplimiento_aceptado');
    }
};

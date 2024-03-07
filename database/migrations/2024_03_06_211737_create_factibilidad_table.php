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
            //al cliente que se le hace el reporte
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            //quien hace el reporte
             $table->unsignedBigInteger('user_id')->nullable();
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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

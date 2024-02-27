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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
             //empresa
             $table->unsignedBigInteger('user_id')->nullable();//usuario
             $table->string('puesto')->nullable();
             $table->string('direccion')->nullable();// calle y numero
             $table->unsignedBigInteger('ctg_cp_id')->nullable();
             $table->string('razon_social')->nullable();
             $table->string('rfc_cliente')->nullable();
             $table->string('phone')->nullable();
             $table->float('resguardo')->default(0);//resguardo de dinero en boveda
             $table->integer('status_user')->default(1);
             $table->unsignedBigInteger('ctg_tipo_cliente_id')->nullable();//fisica-moral
             $table->foreign('ctg_tipo_cliente_id')->references('id')->on('ctg_tipo_cliente')->onDelete('cascade');
             
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             $table->foreign('ctg_cp_id')->references('id')->on('ctg_cp')->onDelete('cascade');
             $table->integer('status_cliente')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

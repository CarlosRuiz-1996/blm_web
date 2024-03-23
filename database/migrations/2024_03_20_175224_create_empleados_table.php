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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();//usuario
            $table->string('direccion')->nullable();// calle y numero
            $table->unsignedBigInteger('ctg_cp_id')->nullable();
            $table->string('sexo')->nullable();
            $table->string('fecha_nacimiento')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('ctg_area_id')->nullable();//fisica-moral
            $table->foreign('ctg_area_id')->references('id')->on('ctg_area')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ctg_cp_id')->references('id')->on('ctg_cp')->onDelete('cascade');
            
            $table->integer('status_empleado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};

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
        Schema::create('validacion_memoranda', function (Blueprint $table) {
            $table->id();
            //sucursal
            $table->unsignedBigInteger('revisor_areas_id')->nullable();
            $table->foreign('revisor_areas_id')->references('id')->on('revisor_areas')->onDelete('cascade');
            
            //tipo memoranda
            $table->unsignedBigInteger('memoranda_id')->nullable();
            $table->foreign('memoranda_id')->references('id')->on('memoranda')->onDelete('cascade');
            $table->integer('status_validacion_memoranda')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validacion_memoranda');
    }
};

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
        // Schema::create('anexo1', function (Blueprint $table) {
        //     // $table->id();
        //     // //coyizacion
        //     // $table->unsignedBigInteger('cotizacion_id')->nullable();
        //     // $table->foreign('cotizacion_id')->references('id')->on('cotizacion')->onDelete('cascade');
            
        //     // $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('anexo1');
    }
};

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
        Schema::create('ctg_denominacions', function (Blueprint $table) {
            $table->id();
            $table->string('denominacion');
            $table->integer('cantidad')->default(0);
            $table->unsignedBigInteger('ctg_tipo_moneda_id');
            $table->foreign('ctg_tipo_moneda_id')->references('id')->on('ctg_tipo_monedas');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctg_denominacions');
    }
};

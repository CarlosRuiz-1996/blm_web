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
        Schema::create('ctg_puestos', function (Blueprint $table) {
            $table->id();
            $table->string('puesto');
            $table->unsignedBigInteger('ctg_area_id');
            $table->foreign('ctg_area_id')->references('id')->on('ctg_area')->onDelete('cascade');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctg_puestos');
    }
};

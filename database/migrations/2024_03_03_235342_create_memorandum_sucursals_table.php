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
        Schema::create('memorandum_sucursal', function (Blueprint $table) {
            $table->id();
            //sucursal
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('cascade');
            //tipo memoranda
            $table->unsignedBigInteger('memoranda_id')->nullable();
            $table->foreign('memoranda_id')->references('id')->on('memoranda')->onDelete('cascade');
            $table->integer('status_memorandum_sucursal')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorandum_sucursal');
    }
};

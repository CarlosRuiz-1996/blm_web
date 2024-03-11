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
        Schema::create('factibilidad_img', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factibilidad_rpt_id')->nullable();
            $table->foreign('factibilidad_rpt_id')->references('id')->on('factibilidad_rpt')->onDelete('cascade');
            $table->string('imagen');
            $table->integer('status_factibilidad_img')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factibilidad_img');
    }
};

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
        Schema::create('cumplimiento_evidencias', function (Blueprint $table) {
            $table->id();
            //cumplimiento
            $table->unsignedBigInteger('cumplimiento_id');
            $table->foreign('cumplimiento_id')->references('id')->on('cumplimiento')->onDelete('cascade');
            $table->string('document_name');
            $table->integer('cumple');
            $table->string('nota');
            $table->integer('status_cumplimiento_evidencias')->default(1);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumplimiento_evidencias');
    }
};

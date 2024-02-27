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
        Schema::create('juridico_doc_validacion', function (Blueprint $table) {
            $table->id();
             //juridico
             $table->unsignedBigInteger('juridico_id');
             $table->foreign('juridico_id')->references('id')->on('juridico')->onDelete('cascade');
             //expediente documento
             $table->unsignedBigInteger('expediente_documentos_id');
             $table->foreign('expediente_documentos_id')->references('id')->on('expediente_documentos')->onDelete('cascade');
             $table->integer('cumple');
             $table->string('nota');
             $table->integer('status_validacion_doc_juridico')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juridico_doc_validacion');
    }
};

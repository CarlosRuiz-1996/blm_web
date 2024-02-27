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
        Schema::create('cumplimiento_doc_validacion_beneficiario', function (Blueprint $table) {
            $table->id();
            //cumplimiento
            $table->unsignedBigInteger('cumplimiento_id');
            $table->foreign('cumplimiento_id')->references('id')->on('cumplimiento')->onDelete('cascade');
            //expediente documento beneficiario
            $table->unsignedBigInteger('expediente_documentos_benf_id');
            $table->foreign('expediente_documentos_benf_id')->references('id')->on('expediente_documentos_benf')->onDelete('cascade');
            $table->integer('cumple');
            $table->string('nota');
            $table->integer('status_validacion_doc_cumplimiento_beneficiario')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumplimiento_doc_validacion_beneficiario');
    }
};

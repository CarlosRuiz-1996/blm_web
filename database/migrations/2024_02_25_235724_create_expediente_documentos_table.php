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
        Schema::create('expediente_documentos', function (Blueprint $table) {
            $table->id();
            //expediente
            $table->unsignedBigInteger('expediente_digital_id');
            $table->foreign('expediente_digital_id')->references('id')->on('expediente_digital')->onDelete('cascade');
            //tipo documento
            $table->unsignedBigInteger('ctg_documentos_id');
            $table->foreign('ctg_documentos_id')->references('id')->on('ctg_documentos')->onDelete('cascade');
            $table->string('document_name');
            $table->integer('status_expediente_doc')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expediente_documentos');
    }
};

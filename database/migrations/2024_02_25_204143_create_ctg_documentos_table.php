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
        Schema::create('ctg_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('status_ctg_doc')->default(1);
            $table->unsignedBigInteger('ctg_tipo_cliente_id');

            $table->foreign('ctg_tipo_cliente_id')->references('id')->on('ctg_tipo_cliente')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctg_documentos');
    }
};

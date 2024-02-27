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
        Schema::create('ctg_cp', function (Blueprint $table) {
            $table->id();
            $table->string('ctg_estado_id');
            $table->string('ctg_municipio_id');
            $table->string('cp');
            $table->string('colonia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctg_cp');
    }
};

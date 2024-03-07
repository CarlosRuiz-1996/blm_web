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
        Schema::create('revisor_areas', function (Blueprint $table) {
            $table->id();
            //user
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //tipo memoranda
            $table->unsignedBigInteger('ctg_area_id')->nullable();
            $table->foreign('ctg_area_id')->references('id')->on('ctg_area')->onDelete('cascade');
            $table->integer('status_revisor_areas')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisor_areas');
    }
};

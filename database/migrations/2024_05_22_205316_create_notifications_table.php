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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            //usuario que envia
            $table->unsignedBigInteger('user_id_send');
            $table->foreign('user_id_send')->references('id')->on('users')->onDelete('cascade');
            //usuario que lo
            $table->unsignedBigInteger('ctg_area_id');
            $table->foreign('ctg_area_id')->references('id')->on('ctg_area')->onDelete('cascade');
            $table->string('message')->nullable();

            $table->integer('status_notificacion')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

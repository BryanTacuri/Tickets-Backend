<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_detalles', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->dateTime('date_delete')->nullable();
            $table->unsignedBigInteger('historial_incidencias_id')->nullable();
            $table->foreign('historial_incidencias_id')->references('id')->on('historial_incidencias')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_detalles');
    }
};
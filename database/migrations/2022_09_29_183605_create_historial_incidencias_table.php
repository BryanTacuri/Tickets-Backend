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
        Schema::create('historial_incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('usuario_soporte');
            $table->string('comentario');
            $table->dateTime('fecha_atencion')->default(now());
            $table->dateTime('date_delete')->nullable();



            $table->unsignedBigInteger('tickets_id')->nullable();
            $table->foreign('tickets_id')->references('id')->on('tickets')->onDelete('set null');

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
        Schema::dropIfExists('historial_incidencias');
    }
};
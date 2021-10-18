<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudesInasistenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_inasistencias', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('aprobado')->nullable();
            $table->date('desde');
            $table->date('hasta');
            $table->string('motivo');
            $table->unsignedinteger('incidencia_id')->nullable();
            $table->foreign('incidencia_id')->references('id')->on('incidencias');
            //verificar en el mes si la cantidad de dias faltados corresponde a los dias de licencias
            $table->unsignedinteger('empleado_id')->nullable();
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->boolean('condicion')->default(1);

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
        Schema::dropIfExists('solicitudes_inasistencias');
    }
}

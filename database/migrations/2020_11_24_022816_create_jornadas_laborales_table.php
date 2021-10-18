<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJornadasLaboralesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jornadas_laborales', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            //08:00,12:00,14:00,17:00
            $table->string('horasTrabajos');
            
            $table->unsignedinteger('empleado_id')->nullable();
            $table->foreign('empleado_id')->references('id')->on('empleados');

            $table->unsignedinteger('solicitudInasistencia_id')->nullable();
            $table->foreign('solicitudInasistencia_id')->references('id')->on('solicitudes_inasistencias');
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
        Schema::dropIfExists('jornadas_laborales');
    }
}

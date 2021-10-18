<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('estadoCivil');
            $table->string('cuil');
            $table->date('fechaNacimiento');
            $table->date('fechaAlta');
            $table->date('fechaBaja')->nullable();
            $table->string('direccion');
            $table->boolean('condicion')->default(1);
            //camino del archivo donde esta el curriculum
            $table->string('curriculum')->nullable();

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
        Schema::dropIfExists('empleados');
    }
}

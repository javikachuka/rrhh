<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactosEmergenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactos_emergencias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('telefono1');
            $table->string('telefono2');
            $table->string('correo');
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
        Schema::dropIfExists('contactos_emergencias');
    }
}

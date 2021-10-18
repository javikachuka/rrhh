<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsabilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //son otras cosas por la cual el empleado podria faltar
        Schema::create('responsabilidades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('descripcion');

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
        Schema::dropIfExists('responsabilidades');
    }
}

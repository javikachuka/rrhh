<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedinteger('rol_id');
            $table->foreign('rol_id')->references('id')->on('roles');
            $table->unsignedinteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->string('usuario')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('condicion')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

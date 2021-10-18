<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiasNoLaboralesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dias_no_laborales', function (Blueprint $table) {
            $table->increments('id');
            $table->date('dia');
            $table->string('descripcion');
            // 25 de mayo siempre es feriado. entonces deberia ser fijo = TRUE.
            // Un dia del mes se tiene que fumigar entonces no se trabaja. fijo = False
            // $table->boolean('fijo');
            $table->boolean('condicion')->default(1);
            $table->unsignedinteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');

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
        Schema::dropIfExists('dias_no_laborales');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas_empresas', function (Blueprint $table) {
            $table->increments('id');       
            $table->unsignedinteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas');
            
            
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
        Schema::dropIfExists('areas_empresas');
    }
}

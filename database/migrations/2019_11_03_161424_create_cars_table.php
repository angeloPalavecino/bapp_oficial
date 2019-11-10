<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
           
            $table->bigIncrements('id');
            $table->string('tipo');
            $table->string('marca');
            $table->string('modelo');
            $table->integer('ano');
            $table->string('motor');
            $table->string('patente');
            $table->string('color');
            $table->string('asientos');
            $table->string('numero_movil');


            $table->boolean('habilitado');
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
        Schema::dropIfExists('cars');
    }
}

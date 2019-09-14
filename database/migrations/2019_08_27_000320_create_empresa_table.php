<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            //$table->unsignedBigInteger('produccion_id');
            //$table->unsignedBigInteger('facturacion_id');
            //$table->unsignedBigInteger('tipo_servicio_id');
            $table->string('rut');
            $table->string('dv', 1);
            $table->string('razon_social');
            $table->string('giro')->nullable(); 
            $table->boolean('habilitado');
            $table->timestamp('fecha_incorporacion');         
            $table->timestamps();


            //$table->foreign('produccion_id')
            //->references('id')
            //->on('cicloproduccions')
            //->onDelete('cascade');

            //$table->foreign('facturacion_id')
            //->references('id')
            //->on('ciclofacturacions')
            //->onDelete('cascade');

            //$table->foreign('tipo_servicio_id')
            //->references('id')
            //->on('tiposervicios')
            //->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}

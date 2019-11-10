<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('name');
            $table->string('lastname');
            $table->integer('rut');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('comuna');
            $table->integer('numeracion');
            $table->string('email')->unique();
            $table->string('clase');
            $table->boolean('dueno');
            $table->boolean('conductor');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('telefono');
            $table->boolean('habilitado');
            $table->timestamps();
            
            $table->foreign('empresa_id')
            ->references('id')
            ->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}

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
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('lastname');
            $table->integer('rut');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('comuna');
            $table->integer('numeracion');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('telefono');
            $table->boolean('habilitado');
            //$table->string('clase');
            //$table->dateTime('carnet_fin')->nullable();
            //$table->dateTime('antecedentes_fin')->nullable();
           // $table->dateTime('licencia_fin')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
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

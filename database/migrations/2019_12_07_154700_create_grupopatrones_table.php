<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrupopatronesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_patrons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sucursal_id');
            $table->string('codigo')->unique();
            $table->string('descripcion');
            $table->string('fuerazona');
            $table->timestamps();

            $table->foreign('sucursal_id')
            ->references('id')
            ->on('sucursals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupo_patrons');
    }
}

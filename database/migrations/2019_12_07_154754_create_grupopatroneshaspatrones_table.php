<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrupopatroneshaspatronesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupopatrones_has_patrones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('grupopatrones_id');
            $table->unsignedBigInteger('patron_id');
            $table->timestamps();

            $table->foreign('grupopatrones_id')
            ->references('id')
            ->on('grupopatrones')
            ->onDelete('cascade');

            $table->foreign('patron_id')
            ->references('id')
            ->on('patrones')
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
        Schema::dropIfExists('grupopatrones_has_patrones');
    }
}

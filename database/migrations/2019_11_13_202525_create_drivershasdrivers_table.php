<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrivershasdriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers_has_drivers', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('asociado_id');
            $table->boolean('habilitado');
            $table->timestamps();

            $table->foreign('driver_id')
            ->references('id')
            ->on('drivers')
            ->onDelete('cascade');

            $table->foreign('asociado_id')
            ->references('id')
            ->on('drivers')
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
        Schema::dropIfExists('drivers_has_drivers');
    }
}

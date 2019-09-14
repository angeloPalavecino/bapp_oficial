<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciosplanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_planas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->decimal('desde', 8, 2);
            $table->decimal('hasta', 8, 2);
            $table->decimal('valormin', 8, 2);
            $table->decimal('valorpsjadicional', 8, 2);
            $table->tinyInteger('tipo');
            $table->decimal('fz1', 8, 2);
            $table->decimal('fz2', 8, 2);
            $table->decimal('fz3', 8, 2);
            $table->timestamps();



            $table->foreign('empresa_id')
            ->references('id')
            ->on('empresas')
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
        Schema::dropIfExists('serviciosplanas');
    }
}

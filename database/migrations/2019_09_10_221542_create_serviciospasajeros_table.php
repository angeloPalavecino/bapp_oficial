<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciospasajerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_pasajeros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->decimal('valorfz1', 8, 2);
            $table->decimal('desdefz1', 8, 2);
            $table->decimal('hastafz1', 8, 2);
            $table->decimal('valorfz2', 8, 2);
            $table->decimal('desdefz2', 8, 2);
            $table->decimal('hastafz2', 8, 2);
            $table->decimal('valorfz3', 8, 2);
            $table->decimal('desdefz3', 8, 2);
            $table->decimal('hastafz3', 8, 2);
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
        Schema::dropIfExists('serviciospasajeros');
    }
}

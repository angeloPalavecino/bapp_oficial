<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicioskmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_k_m_s_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->decimal('valor', 8, 2);
            $table->decimal('valorportico', 8, 2);
            $table->decimal('distancia', 8, 2);
            $table->integer('tiempo');
            $table->integer('pasajeros');
            $table->decimal('bajabandera', 8, 2);
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
        Schema::dropIfExists('servicioskms');
    }
}

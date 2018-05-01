<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('partidas', function (Blueprint $table) {
          $table->increments('id');
          $table->boolean('turno');
          $table->integer('estado');
          $table->string('jugador0_id');
          $table->string('jugador1_id');
          $table->timestamps();
      });
      Schema::create('fichas', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('fila');
          $table->integer('col');
          $table->integer('partida_id')->unsigned();
          $table->foreign('partida_id')->references('id')->on('partidas');
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
        Schema::dropIfExists('partidas');
    }
}

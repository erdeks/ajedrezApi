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
          $table->integer('jugador0_id')->unsigned();
          $table->integer('jugador1_id')->unsigned();
          $table->foreign('jugador0_id')->references('id')->on('users');
          $table->foreign('jugador1_id')->references('id')->on('users');
          $table->timestamps();
      });
      Schema::create('fichas', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('figura'); // caldrà definir-les al model
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

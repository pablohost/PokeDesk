<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pokemon_abilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokemon_id');
            $table->foreignId('ability_id');
            $table->timestamps();

            $table->foreign('pokemon_id')->references('id')->on('pokemon');
            $table->foreign('ability_id')->references('id')->on('abilities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pokemon_abilities');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmCharacterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('film_character', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('film_id');
            $table->integer('character_id');
            $table->unique(['film_id', 'character_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('film_character', function (Blueprint $table) {
            //
        });
    }
}

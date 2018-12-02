<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDsdetalhesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsdetalhes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('datasheets_id')->unsigned();
            $table->integer('quantidade');
            $table->integer('dsnomes_id')->unsigned();
            $table->integer('dstipos_id')->unsigned();
            $table->integer('dslocais_id')->unsigned();
            $table->timestamps();
        });
        
        Schema::table('dsdetalhes', function($table) {
            $table->foreign('datasheets_id')->references('id')->on('datasheets')->onDelete('cascade');
            $table->foreign('dsnomes_id')->references('id')->on('dsnomes')->onDelete('cascade');
            $table->foreign('dstipos_id')->references('id')->on('dstipos')->onDelete('cascade');
            $table->foreign('dslocais_id')->references('id')->on('dslocais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dsdetalhes');
    }
}

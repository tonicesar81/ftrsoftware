<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRessalvasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ressalvas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('relatorios_id')->unsigned();
            $table->text('mensagem')->nullable();
            $table->timestamps();
        });
        
        Schema::table('ressalvas', function($table) {
            $table->foreign('relatorios_id')->references('id')->on('relatorios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ressalvas');
    }
}

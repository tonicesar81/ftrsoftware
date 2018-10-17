<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFigurasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('figuras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('relatorios_id')->unsigned();
            $table->string('figura');
            $table->timestamps();
        });
        
        Schema::table('figuras', function($table) {
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
        Schema::dropIfExists('figuras');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDsnomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsnomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_relatorios_id')->unsigned();
            $table->string('nome');
            $table->string('nome_plural')->nullable();
            $table->timestamps();
        });
        
        Schema::table('dsnomes', function($table) {
            $table->foreign('tipo_relatorios_id')->references('id')->on('tipo_relatorios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dsnomes');
    }
}

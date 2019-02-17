<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObrasArquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obras_arquivos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shoppings_id')->unsigned();
            $table->integer('tipo');
            $table->string('arquivo');
            $table->string('hash');
            $table->timestamps();
        });
        
        Schema::table('obras_arquivos', function($table) {
            $table->foreign('shoppings_id')->references('id')->on('shoppings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obras_arquivos');
    }
}

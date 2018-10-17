<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('relatorios_id')->unsigned();
            $table->integer('itens_id')->unsigned();
            $table->text('comentario');
            $table->timestamps();
        });
        
        Schema::table('comentarios', function($table) {
            $table->foreign('relatorios_id')->references('id')->on('relatorios');
            $table->foreign('itens_id')->references('id')->on('itens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comentarios');
    }
}

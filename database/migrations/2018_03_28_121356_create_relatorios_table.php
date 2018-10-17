<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relatorios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('loja');
            $table->integer('shoppings_id')->unsigned();
            $table->integer('referencia')->unsigned()->nullable();
            $table->integer('revisao')->default(0);
            $table->integer('users_id')->unsigned();
            $table->string('id_arquivo')->default('###');
            $table->text('analise')->nullable();
            $table->integer('tipo_relatorios_id')->unsigned();
            $table->timestamps();
        });
        
        Schema::table('relatorios', function($table) {
            $table->foreign('shoppings_id')->references('id')->on('shoppings')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tipo_relatorios_id')->references('id')->on('tipo_relatorios')->onDelete('cascade');
            $table->foreign('referencia')->references('id')->on('relatorios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relatorios');
    }
}

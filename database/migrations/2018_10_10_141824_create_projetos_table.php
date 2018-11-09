<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjetosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('projetos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shoppings_id')->unsigned();
            $table->integer('tipo_relatorios_id')->unsigned();
            $table->string('loja');
            $table->timestamps();
        });

        Schema::table('projetos', function($table) {
            $table->foreign('shoppings_id')->references('id')->on('shoppings')->onDelete('cascade');
            $table->foreign('tipo_relatorios_id')->references('id')->on('tipo_relatorios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('projetos');
    }

}

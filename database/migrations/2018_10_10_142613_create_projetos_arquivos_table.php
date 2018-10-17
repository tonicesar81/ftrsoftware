<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjetosArquivosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('projetos_arquivos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projetos_id')->unsigned();
            $table->integer('tipo_relatorios_id')->unsigned();
            $table->string('filename');
            $table->string('filepath');
            $table->integer('memorial')->nullable();
            $table->timestamps();
        });

        Schema::table('projetos_arquivos', function($table) {
            $table->foreign('projetos_id')->references('id')->on('projetos')->onDelete('cascade');
            $table->foreign('tipo_relatorios_id')->references('id')->on('tipo_relatorios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('projetos_arquivos');
    }

}

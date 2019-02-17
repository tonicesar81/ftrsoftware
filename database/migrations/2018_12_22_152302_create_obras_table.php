<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->integer('users_id')->unsigned();
            $table->integer('shoppings_id')->unsigned();
            $table->string('numero');
            $table->text('introducao');
            $table->text('conclusao');
            $table->timestamps();
        });
        
        Schema::table('obras', function($table) {
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('obras');
    }
}

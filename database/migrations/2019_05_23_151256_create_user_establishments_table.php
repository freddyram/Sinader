<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEstablishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_establishments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('user');
            $table->integer('establishment_id')->references('id')->on('establishment');
            $table->integer('retc_id');
            $table->string('secret_a')->nullable();
            $table->string('secret_b')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_establishments');
    }
}

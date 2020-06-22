<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstablishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establishments', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->string('street')->nullable();
            $table->integer('number')->nullable();
            $table->integer('commune_id')->references('id')->on('commune');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('company_id')->references('id')->on('companies');
            $table->string('ciiu_id')->nullable();
            $table->string('city')->nullable();
            $table->integer('region_id')->references('id')->on('region')->nullable();
            $table->integer('user_id')->references('id')->on('users')->nullable();
            $table->integer('retc_code')->nullable();
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
        Schema::dropIfExists('establishments');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('fantasy_name')->nullable();
            $table->integer('rut');
            $table->string('digit');
            $table->string('street')->nullable();
            $table->integer('number')->nullable();
            $table->integer('commune_id')->references('id')->on('commune');
            $table->string('email')->nullable();
            $table->integer('retc_code');
            $table->integer('rut_legal_representative')->nullable();
            $table->string('digit_legal_representative')->nullable();
            $table->string('name_legal_representative')->nullable();
            $table->string('email_legal_representative')->nullable();
            $table->integer('user_id')->references('id')->on('user');
            $table->string('city')->nullable();
            $table->integer('region_id')->references('id')->on('region')->nullable();

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
        Schema::dropIfExists('companies');
    }
}

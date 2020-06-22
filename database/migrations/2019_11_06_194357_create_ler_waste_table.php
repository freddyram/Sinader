<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLerWasteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ler_waste', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('waste_number');
            $table->integer('subchapter_id')->references('id')->on('ler_subchapter');
            $table->text('name');
            $table->string('waste_code');
            $table->string('type_id')->references('id')->on('waste_types');;
            $table->integer('active');
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
        Schema::dropIfExists('ler_waste');
    }
}

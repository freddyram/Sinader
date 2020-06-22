<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLerSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ler_subchapter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('subchapter_number');
            $table->text('name');
            $table->integer('chapter_id')->references('id')->on('ler_chapter');
            $table->integer("active");
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
        Schema::dropIfExists('ler_subchapter');
    }
}

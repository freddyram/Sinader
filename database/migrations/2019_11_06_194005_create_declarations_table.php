<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('declaration_origin_id')->nullable();
            $table->integer("correlative");
            $table->string("correlative_dv");
            $table->string("rut")->nullable();
            $table->string("company")->nullable();
            $table->string("establishment")->nullable();
            $table->string("direccion")->nullable();
            $table->string("comuna")->nullable();
            $table->string("region")->nullable();

            $table->integer("establishment_id")->references('id')->on('establishment');
            $table->integer("user_id")->references('id')->on('user');
            $table->string("type");
            $table->string("period");
            $table->string("status");
            $table->string("certificate")->nullable();
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
        Schema::dropIfExists('declarations');
    }
}

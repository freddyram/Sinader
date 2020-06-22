<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthWastesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('month_wastes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ler');
            $table->string('rut');
            $table->integer('entablishment');
            $table->integer('process');
            $table->double('quantity', 12, 4);
            $table->string('carrier');
            $table->string('plate');
            $table->string('date');
            $table->integer('manage');
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
        Schema::dropIfExists('month_wastes');
    }
}

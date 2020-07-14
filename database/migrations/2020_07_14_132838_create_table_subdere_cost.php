<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSubdereCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subdere_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('declaration_id');
            $table->integer('cobertura_urbana')->nullable(); 
            $table->integer('cobertura_rural')->nullable();
            $table->integer('frecuencia_Urbana')->nullable();
            $table->integer('frecuencia_Rural')->nullable();
            $table->integer('costo_recol_transp')->nullable();
            $table->integer('costo_tonelada_recol')->nullable();
            $table->integer('costo_disp_final')->nullable();
            $table->integer('costo_tonelada_disp')->nullable();
            $table->integer('recaudacion_derecho_aseo')->nullable();
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
        Schema::dropIfExists('subdere_costs');
    }
}

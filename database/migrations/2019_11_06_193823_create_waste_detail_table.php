<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWasteDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waste_details', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->integer('declaration_id')->references('id')->on('declaration');

            $table->string('waste');
            $table->string('chapter');
            $table->string('subchapter');

            $table->string('company')->nullable();
            $table->string('establishment')->nullable();
            $table->string('processing')->nullable();
            $table->string('gestion')->nullable();
            $table->string('recolection')->nullable();
            
            $table->string('pais')->nullable();
            $table->string('empresa')->nullable();
            $table->string('contacto')->nullable();
            $table->string('email')->nullable();
            
            $table->string('status');
            $table->integer('discrep_quantity')->nullable();
            $table->string('discrep_unit')->nullable();
            $table->string('discrep_comment')->nullable();


            
            $table->double('quantity', 12 , 4);
            $table->integer('waste_id')->references('id')->on('ler_waste');
            $table->integer('chapter_id')->references('id')->on('ler_chapter');
            $table->integer('subchapter_id')->references('id')->on('ler_subchapter');

            $table->integer('company_id')->nullable()->references('id')->on('companies');
            $table->integer('establishment_id')->nullable()->references('id')->on('establishment');

            $table->integer('manage_id')->nullable()->references('id')->on('manage_types');
            $table->integer('process_id')->nullable()->references('id')->on('process_types');
            $table->integer('unit_id')->references('id')->on('units');
            $table->integer('recolection_id')->nullable()->references('id')->on('recolection_types');



            $table->integer('carrier_id')->nullable()->references('id')->on('carriers');
            $table->string('carrier_name')->nullable();
            $table->date('trasnport_date')->nullable();
            
            $table->integer('plate_id')->nullable()->references('plate')->on('vehicle');
            $table->string('plate')->nullable();


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
        Schema::dropIfExists('waste_details');
    }
}

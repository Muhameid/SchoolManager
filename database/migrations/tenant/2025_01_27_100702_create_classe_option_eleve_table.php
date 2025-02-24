<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classe_option_eleve', function (Blueprint $table) {
            $table->bigInteger('classe_option_id');
            $table->bigInteger('eleve_id');
            $table->primary(['classe_option_id','eleve_id']); //clé etranger enclé primaire 

            $table->index('classe_option_id');//clé étranger
            $table->foreign('classe_option_id')->references('id')->on('classe_options');
            
            $table->index('eleve_id');
            $table->foreign('eleve_id')->references('id')->on('eleves');
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
        Schema::dropIfExists('classe_option_eleve');
    }
};
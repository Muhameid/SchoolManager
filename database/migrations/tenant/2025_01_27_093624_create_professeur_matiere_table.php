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
        Schema::create('professeur_matiere', function (Blueprint $table) {
            $table->bigInteger('professeur_id');
            $table->bigInteger('matiere_id');
            
            $table->primary(['professeur_id', 'matiere_id']);
            $table->index('professeur_id');
            $table->foreign('professeur_id')->references('id')->on('professeurs');

            $table->index('matiere_id');
            $table->foreign('matiere_id')->references('id')->on('matieres');
            
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
        Schema::dropIfExists('professeur_matiere');
    }
};

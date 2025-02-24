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
        Schema::create('classe_matiere', function (Blueprint $table) {
            $table->bigInteger('classe_id');
            $table->bigInteger('matiere_id');
            $table->bigInteger('professeur_id');

            $table->primary(['classe_id', 'matiere_id']);
            
            $table->index('classe_id');
            $table->foreign('classe_id')->references('id')->on('classes');

            $table->index('matiere_id');
            $table->foreign('matiere_id')->references('id')->on('matieres');
            
            $table->index('professeur_id')->nullable();
            $table->foreign('professeur_id')->references('id')->on('professeurs');
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
        Schema::dropIfExists('classe_matiere');
    }
};

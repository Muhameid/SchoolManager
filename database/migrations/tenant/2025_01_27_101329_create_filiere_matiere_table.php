<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filiere_matiere', function (Blueprint $table) {
            $table->bigInteger('filiere_id');
            $table->bigInteger('matiere_id');
            $table->bigInteger('professeur_id')->nullable();
            $table->smallInteger('coefficient');

            $table->primary(['filiere_id','matiere_id']);

            $table->index('filiere_id');
            $table->foreign('filiere_id')->references('id')->on('filieres');

            $table->index('matiere_id');
            $table->foreign('matiere_id')->references('id')->on('matieres');

            $table->index('professeur_id');
            $table->foreign('professeur_id')->references('id')->on('professeurs');
            $table->timestamps();
        });
        DB::statement('Alter TABLE filiere_matiere ADD CONSTRAINT coefficient_range_2 CHECK ( coefficient>0 AND coefficient <=40 )');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filiere_matiere');
    }
};
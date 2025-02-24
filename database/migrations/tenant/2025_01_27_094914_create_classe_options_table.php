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
        Schema::create('classe_options', function (Blueprint $table) {
            $table->id();
            $table->char('coefficient')->default(2);
            $table->string('nom',100);

            $table->bigInteger('profeseur_id')->nullable(); 
            $table->index('profeseur_id');
            $table->foreign('profeseur_id')->references('id')->on('professeurs');

            $table->bigInteger('matiere_id');
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
        Schema::dropIfExists('classe_options');
    }
};
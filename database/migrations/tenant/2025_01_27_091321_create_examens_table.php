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
        Schema::create('examens', function (Blueprint $table) {
            $table->id();$table->timestamp('date_examen');
            $table->string('name',50);
            $table->text('sujet',50);
            $table->string('lien',1000)->nullable();
            $table->smallInteger('coefficient')->default(1);
            $table->bigInteger('matiere_id');
            $table->bigInteger('professeur_id');
            $table->index('matiere_id');
            $table->foreign('matiere_id')->references('id')->on('matieres');
            $table->index('professeur_id');
            $table->foreign('professeur_id')->references('id')->on('professeurs');
            $table->timestamps();
        
        }); DB::statement('Alter TABLE examens ADD CONSTRAINT coefficient_range CHECK ( coefficient>0 AND coefficient <=40 )');

    }

    /*
     
Reverse the migrations.*
@return void*/
public function down(){Schema::dropIfExists('examens');}
};
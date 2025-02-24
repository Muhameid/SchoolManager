<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eleve_examen', function (Blueprint $table) {
            $table->bigInteger('eleve_id');
            $table->bigInteger('examen_id');
            $table->float('note')->nullable();
            $table->text('observation')->nullable();
            $table->string('lien',1000)->nullable();
            $table->primary(['eleve_id','examen_id']);

            $table->index('eleve_id');
            $table->foreign('eleve_id')->references('id')->on('eleves');

            $table->index('examen_id');
            $table->foreign('examen_id')->references('id')->on('examens');
            $table->timestamps();
        });
        DB::statement('Alter TABLE eleve_examen ADD CONSTRAINT note_range CHECK ( (note>=0 AND note <=20) OR note is NULL) ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eleve_examen');
    }
};
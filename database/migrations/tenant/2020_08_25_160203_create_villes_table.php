<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVillesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('villes', function (Blueprint $table) {
            $table->bigInteger('id')->primary(); // avant : $table->id();
            $table->timestamps();
            $table->string('code_ville',20)->default('');
            $table->string('nom',70);
            $table->bigInteger('pays_id');
            $table->string('fuseaux',100)->nullable();
            $table->index('pays_id');
            $table->foreign('pays_id')->references('id')->on('pays');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('villes');
    }
}
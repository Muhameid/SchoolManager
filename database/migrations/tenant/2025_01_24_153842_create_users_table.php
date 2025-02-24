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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login',50)->unique();
            $table->string('nom',100);
            $table->string('prenom',100);

            $table->bigInteger('usereable_id');
            $table->bigInteger('ville_id');

            $table->string('usereable_type',100);
            $table->unique(['usereable_id','usereable_type']);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->string('adresse',1000);
            $table->timestamp('date_naissance');
            $table->string('telephone_1',40);
            $table->string('telephone_2',40);
            $table->string('telephone_3',40);
            $table->index('ville_id');
            $table->foreign('ville_id')->references('id')->on('villes');
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
        Schema::dropIfExists('users');
    }
};
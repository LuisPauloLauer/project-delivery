<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class States extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('States', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country');
            $table->string('name')->nullable(false);
            $table->char('uf',2)->nullable(false);
            $table->char('ibge',2)->nullable(false);
            $table->string('ddd')->nullable(false);
            $table->foreign('country')->references('id')->on('Countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('States');
    }
}

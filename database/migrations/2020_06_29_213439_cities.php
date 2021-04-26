<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('state');
            $table->string('name')->nullable(false);
            $table->char('ibge',7)->nullable(false);
            $table->foreign('state')->references('id')->on('States');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Cities');
    }
}

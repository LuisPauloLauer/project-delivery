<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocationUsersSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LocationUsersSite', function (Blueprint $table) {
            $table->id();
            $table->char('enable',1)->default('N')->nullable(false);
            $table->unsignedBigInteger('usersite');
            $table->unsignedBigInteger('tplocation');
            $table->char('zip_code',8)->nullable();
            $table->string('street')->nullable(false);
            $table->char('number', 9)->nullable(false);
            $table->string('district')->nullable(false);
            $table->string('complement')->nullable();
            $table->unsignedBigInteger('city');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            $table->foreign('usersite')->references('id')->on('UsersSite');
            $table->foreign('tplocation')->references('id')->on('TpLocationUsersSite');
            $table->foreign('city')->references('id')->on('Cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('LocationUsersSite');
    }
}

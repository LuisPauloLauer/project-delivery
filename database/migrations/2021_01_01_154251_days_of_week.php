<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DaysOfWeek extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DaysOfWeek', function (Blueprint $table) {
            $table->id();
            $table->char('status',1)->default('N')->nullable(false);
            $table->integer('sequence')->nullable(false);
            $table->string('day', '9')->nullable(false);
            $table->string('language', '5')->nullable(false);
            $table->timestamps();
            $table->unique(['sequence', 'day', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DaysOfWeek');
    }
}

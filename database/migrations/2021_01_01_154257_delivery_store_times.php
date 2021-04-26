<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeliveryStoreTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DeliveryStoreTimes', function (Blueprint $table) {
            $table->id();
            $table->char('status',1)->default('N')->nullable(false);
            $table->unsignedBigInteger('store');
            $table->unsignedBigInteger('day');
            $table->time('periodo1_ini')->nullable(false)->default('00:00');
            $table->time('periodo1_end')->nullable(false)->default('00:00');
            $table->time('periodo2_ini')->nullable(false)->default('00:00');
            $table->time('periodo2_end')->nullable(false)->default('00:00');
            $table->timestamps();
            $table->foreign('store')->references('id')->on('Stores');
            $table->foreign('day')->references('id')->on('DaysOfWeek');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DeliveryStoreTimes');
    }
}

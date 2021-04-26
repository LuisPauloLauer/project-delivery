<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoresPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Stores_Payment', function (Blueprint $table) {
            $table->id();
            $table->char('status',1)->default('N')->nullable(false);
            $table->unsignedBigInteger('store');
            $table->string('type_payment_local')->nullable(false);
            $table->string('type_payment_system')->nullable(false);
            $table->string('type_payment_origin')->nullable(false);
            $table->string('type_payment_name')->nullable(false);
            $table->string('type_payment_flag')->nullable(false);
            $table->foreign('store')->references('id')->on('Stores');
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
        Schema::dropIfExists('Stores_Payment');
    }
}

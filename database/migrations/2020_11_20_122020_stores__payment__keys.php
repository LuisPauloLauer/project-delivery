<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoresPaymentKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Stores_Payment_Keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_payment_system');
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->foreign('type_payment_system')->references('id')->on('Stores_Payment');
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
        Schema::dropIfExists('Stores_Payment_Keys');
    }
}

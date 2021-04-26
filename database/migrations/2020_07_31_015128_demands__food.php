<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DemandsFood extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Demands_Food', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status');
            $table->unsignedBigInteger('store');
            $table->unsignedBigInteger('user_site');
            $table->string('type_deliver')->nullable(false);
            $table->string('type_payment')->nullable(false);
            $table->string('invoice_number')->unique()->nullable(false);
            $table->string('currency_payment')->nullable(false);
            $table->decimal('total_amount', 16, 4)->nullable(false);
            $table->decimal('sub_total_price', 16, 4)->nullable(false);
            $table->decimal('tax_price', 16, 4)->nullable(false);
            $table->decimal('shipping_price', 16, 4)->nullable(false);
            $table->decimal('shipping_discount_price', 16, 4)->nullable(false);
            $table->decimal('insurance_price', 16, 4)->nullable(false);
            $table->decimal('handling_fee_price', 16, 4)->nullable(false);
            $table->decimal('total_price', 16, 4)->nullable(false);
            $table->string('coupon')->nullable();
            $table->timestamps();
            $table->foreign('status')->references('id')->on('Status_Demands_Food');
            $table->foreign('store')->references('id')->on('Stores');
            $table->foreign('user_site')->references('id')->on('UsersSite');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Demands_Food');
    }
}

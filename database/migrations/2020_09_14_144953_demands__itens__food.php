<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DemandsItensFood extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Demands_Itens_Food', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand');
            $table->integer('kit_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->decimal('amount', 16, 4)->nullable(false);
            $table->string('observation')->nullable();
            $table->string('kit_sub_itens')->nullable();
            $table->timestamps();
            $table->foreign('demand')->references('id')->on('Demands_Food');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Demands_Itens_Food');
    }
}

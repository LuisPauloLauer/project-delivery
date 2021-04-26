<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RelKitsProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Rel_Kits_Products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kit');
            $table->unsignedBigInteger('product');
            $table->timestamps();
            $table->foreign('kit')->references('id')->on('Kits');
            $table->foreign('product')->references('id')->on('Products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Rel_Kits_Products');
    }
}

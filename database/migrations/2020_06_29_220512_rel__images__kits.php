<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RelImagesKits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Rel_Images_Kits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kit');
            $table->unsignedBigInteger('store');
            $table->string('path_image')->nullable();
            $table->timestamps();
            $table->foreign('kit')->references('id')->on('Kits')->onDelete('cascade');
            $table->foreign('store')->references('id')->on('Stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Rel_Images_Kits');
    }
}

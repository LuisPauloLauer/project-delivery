<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategoriesProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CategoriesProduct', function (Blueprint $table) {
            $table->id();
            $table->char('status',1)->default('N')->nullable(false);
            $table->unsignedBigInteger('store');
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->text('description')->nullable();
            $table->integer('n_order')->nullable(false)->default(1);
            $table->timestamps();
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
        Schema::dropIfExists('CategoriesProduct');
    }
}

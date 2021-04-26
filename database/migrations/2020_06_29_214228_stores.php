<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Stores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Stores', function (Blueprint $table) {
            $table->id();
            $table->char('status',1)->default('N')->nullable(false);
            $table->char('active_store_site',1)->default('N')->nullable(false);
            $table->unsignedBigInteger('affiliate');
            $table->unsignedBigInteger('segment');
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->char('zip_code',8)->nullable(false);
            $table->string('street')->nullable(false);
            $table->char('number', 9)->nullable(false);
            $table->string('district')->nullable(false);
            $table->string('complement')->nullable();
            $table->unsignedBigInteger('city');
            $table->char('fone1',11)->nullable(false);
            $table->char('fone2',11)->nullable();
            $table->char('fone_store_site',11)->nullable();
            $table->string('email')->nullable(false);
            $table->decimal('minimum_order', 16, 4)->nullable();
            $table->text('description')->nullable(false);
            $table->string('path_image_capa')->nullable();
            $table->string('path_image_logo')->nullable();
            $table->timestamps();
            $table->foreign('affiliate')->references('id')->on('Affiliates');
            $table->foreign('segment')->references('id')->on('Segments');
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
        Schema::dropIfExists('Stores');
    }
}

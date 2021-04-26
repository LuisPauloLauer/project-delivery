<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Kits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Kits', function (Blueprint $table) {
            $table->id();
            $table->char('status',1)->default('N')->nullable(false);
            $table->unsignedBigInteger('store');
            $table->string('id_pdv_store')->nullable();
            $table->string('codigo_pdv_store')->nullable();
            $table->string('codigo_barras_pdv_store')->nullable();
            $table->unsignedBigInteger('category_product');
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->decimal('amount', 16, 4)->nullable(false);
            $table->decimal('unit_price', 16, 4)->nullable(false);
            $table->decimal('unit_promotion_price', 16, 4)->nullable();
            $table->double('unit_discount', 5, 2)->nullable();
            $table->text('description')->nullable(false);
            $table->integer('views')->nullable();
            $table->integer('sold')->nullable();
            $table->timestamps();
            $table->foreign('store')->references('id')->on('Stores');
            $table->foreign('category_product')->references('id')->on('CategoriesProduct');
            $table->unique(['store', 'id_pdv_store']);
            $table->unique(['store', 'codigo_pdv_store']);
            $table->unique(['store', 'codigo_barras_pdv_store']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Kits');
    }
}

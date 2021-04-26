<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Affiliates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Affiliates', function (Blueprint $table) {
            $table->id();
            $table->char('status',1)->default('N')->nullable(false);
            $table->unsignedBigInteger('tpaffiliate');
            $table->char('type_person', 2)->nullable(false);
            $table->string('corporate_name')->nullable(false);
            $table->string('fantasy_name')->nullable(false);
            $table->char('zip_code',8)->nullable(false);
            $table->string('street')->nullable(false);
            $table->char('number', 9)->nullable(false);
            $table->string('district')->nullable(false);
            $table->string('complement')->nullable();
            $table->unsignedBigInteger('city');
            $table->char('fone1',11)->nullable(false);
            $table->char('fone2',11)->nullable();
            $table->char('cpf',11)->nullable();
            $table->char('cnpj',14)->nullable();
            $table->string('email')->nullable(false);
            $table->timestamps();
            $table->foreign('tpaffiliate')->references('id')->on('TpAffiliates');
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
        Schema::dropIfExists('Affiliates');
    }
}

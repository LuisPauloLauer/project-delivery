<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RelStoresUsersAdm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Rel_Stores_UsersAdm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store');
            $table->unsignedBigInteger('useradm');
            $table->timestamps();
            $table->foreign('store')->references('id')->on('Stores');
            $table->foreign('useradm')->references('id')->on('UsersAdm')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Rel_Stores_UsersAdm');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TpUsersAdm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TpUsersAdm', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable(false);
            $table->string('name')->nullable(false);
            $table->text('description')->nullable()->nullable(false);
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
        Schema::dropIfExists('TpUsersAdm');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UsersAdm', function (Blueprint $table) {
            $table->id();
            $table->char('status',1)->default('N')->nullable(false);
            $table->unsignedBigInteger('tpuser');
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->char('cpf',11)->unique()->nullable(false);
            $table->date('birth')->nullable(false);
            $table->char('sex',1)->nullable(false);
            $table->char('fone',11)->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            $table->string('path_image')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('tpuser')->references('id')->on('TpUsersAdm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('UsersAdm');
    }
}

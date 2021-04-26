<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UsersSite', function (Blueprint $table) {
            $table->id();
            $table->char('status',1)->default('N')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->char('cpf',11)->unique()->nullable();
            $table->date('birth')->nullable();
            $table->char('sex',1)->nullable();
            $table->char('fone',11)->unique()->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('path_image')->nullable();
            //$table->rememberToken();
            $table->string('remember_token')->unique()->nullable(false);
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
        Schema::dropIfExists('UsersSite');
    }
}

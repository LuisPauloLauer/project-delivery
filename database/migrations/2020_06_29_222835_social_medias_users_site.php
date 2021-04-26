<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SocialMediasUsersSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SocialMediasUsersSite', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_site_id');
            $table->string('provider_name')->nullable(false);
            $table->string('provider_id')->nullable(false);
            $table->string('email')->unique()->nullable();
            $table->timestamps();
            $table->foreign('user_site_id')->references('id')->on('UsersSite')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SocialMediasUsersSite');
    }
}

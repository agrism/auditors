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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('0');
            $table->string('email', 255)->nullable();
            $table->string('password', 60)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('provider', 50)->nullable();
            $table->string('provider_id', 100)->nullable()->unique('provider_id');
            $table->rememberToken();
            $table->integer('is_admin')->nullable();
            $table->integer('is_developer')->nullable();
            $table->timestamps()->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

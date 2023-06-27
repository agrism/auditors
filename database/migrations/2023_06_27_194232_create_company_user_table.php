<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_user', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            
            $table->primary(['company_id', 'user_id']);
            $table->foreign('company_id', 'FK__clients')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id', 'FK__users')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_user');
    }
}

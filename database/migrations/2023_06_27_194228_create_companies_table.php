<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('registration_number', 50)->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('swift', 50)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->date('closed_data_date')->nullable();
            $table->integer('settings_top-margin')->default(0);
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
        Schema::dropIfExists('companies');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->string('registration_number', 50)->nullable();
            $table->string('vat_number', 50)->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('swift', 50)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->timestamps();
            
            $table->foreign('company_id', 'FK_partners_companies')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
}

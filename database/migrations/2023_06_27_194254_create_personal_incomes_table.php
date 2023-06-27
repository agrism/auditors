<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_incomes', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('partner_id')->nullable();
            $table->string('partner_name_text', 255)->nullable();
            $table->string('partner_registration_number_text', 15)->nullable();
            $table->integer('personal_income_type_id')->nullable();
            $table->string('personal_income_type_text', 50)->nullable();
            $table->date('income_period_date_from')->nullable();
            $table->date('income_period_date_till')->nullable();
            $table->date('income_paid_date')->nullable();
            $table->float('income_gross_amount')->nullable();
            $table->integer('personal_income_cost_rate_id')->nullable();
            $table->float('personal_income_cost_rate_text')->nullable();
            $table->integer('income_tax_rate_id')->nullable();
            $table->float('income_tax_rate_text')->nullable();
            $table->integer('creator_user_jd')->nullable();
            $table->integer('company_id')->nullable();
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
        Schema::dropIfExists('personal_incomes');
    }
}

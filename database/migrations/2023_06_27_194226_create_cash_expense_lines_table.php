<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashExpenseLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_expense_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            $table->integer('cash_expenses_id')->nullable();
            $table->date('date')->nullable();
            $table->integer('partner_id')->nullable();
            $table->integer('no')->nullable();
            $table->string('document_no', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->float('amount_without_vat', 20, 2)->nullable();
            $table->float('amount_vat', 20, 2)->nullable();
            $table->float('amount_with_vat', 20, 2)->nullable();
            $table->integer('budget_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->string('vat_calculator_name', 255)->nullable();
            $table->longText('vat_calculation')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_expense_lines');
    }
}

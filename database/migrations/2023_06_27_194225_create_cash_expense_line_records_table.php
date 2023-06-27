<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashExpenseLineRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_expense_line_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_expense_lines_id')->nullable();
            $table->float('amount')->nullable();
            $table->integer('debet_account_id')->nullable();
            $table->integer('budget_id')->nullable();
            $table->integer('vat_id')->nullable();
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
        Schema::dropIfExists('cash_expense_line_records');
    }
}

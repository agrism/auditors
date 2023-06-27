<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceLines1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_lines_1', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invoice_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->string('code', 20)->nullable();
            $table->string('title')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->float('quantity', 20, 3)->default(0.000);
            $table->integer('currency_id')->default(0);
            $table->float('price', 10, 3)->default(0.000);
            $table->integer('vat_id')->default(0);
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
        Schema::dropIfExists('invoice_lines_1');
    }
}

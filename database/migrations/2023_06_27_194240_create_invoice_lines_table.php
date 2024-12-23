<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invoice_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->string('code', 20)->nullable();
            $table->string('title', 350)->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->float('quantity')->default(0);
            $table->integer('currency_id')->default(0);
            $table->float('price', 20, 3)->default(0.000);
            $table->integer('vat_id')->default(0);
            $table->timestamps();

            $table->foreign('invoice_id', 'FK_invoice_lines_invoices')->references('id')->on('invoices');
            $table->foreign('vat_id', 'FK_invoice_lines_vats')->references('id')->on('vats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_lines');
    }
}

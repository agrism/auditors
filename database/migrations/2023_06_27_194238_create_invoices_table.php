<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 50)->nullable();
            $table->date('date')->nullable();
            $table->date('payment_date')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('vat_number', 50)->nullable();
            $table->integer('partner_id')->nullable();
            $table->string('partner_name', 255)->nullable();
            $table->string('partner_address', 255)->nullable();
            $table->string('partner_registration_number', 255)->nullable();
            $table->string('partner_vat_number', 255)->nullable();
            $table->string('partner_bank', 255)->nullable();
            $table->string('partner_swift', 255)->nullable();
            $table->string('partner_account_number', 255)->nullable();
            $table->string('payment_receiver', 255)->nullable();
            $table->string('bank', 25)->nullable();
            $table->string('swift', 255)->nullable();
            $table->string('account_number', 255)->nullable();
            $table->string('goods_address_from', 255)->nullable();
            $table->string('goods_address_to', 255)->nullable();
            $table->string('goods_deliverer', 255)->nullable();
            $table->string('details')->nullable();
            $table->string('details1')->nullable();
            $table->string('details_self')->nullable();
            $table->text('details_bottom1')->nullable();
            $table->text('details_bottom2')->nullable();
            $table->text('details_bottom3')->nullable();
            $table->string('document_signer')->nullable();
            $table->string('document_partner_signer')->nullable();
            $table->integer('currency_id')->nullable();
            $table->integer('structuralunit_id')->nullable();
            $table->integer('invoicetype_id')->nullable();
            $table->float('currency_rate')->nullable();
            $table->float('amount_total')->nullable();
            $table->float('amount_tatal_base_currency')->nullable();
            $table->boolean('is_locked')->nullable();
            $table->integer('locker_user_id')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}

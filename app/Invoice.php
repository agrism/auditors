<?php

namespace App;

use App\Services\SelectedCompanyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Invoice
 * @property int id
 * @property string number
 * @property string date
 * @property string payment_date
 * @property int company_id
 * @property string vat_number
 * @property int partner_id
 * @property string partner_name
 * @property string partner_address
 * @property string partner_registration_number
 * @property string partner_vat_number
 * @property string partner_bank
 * @property string partner_swift
 * @property string partner_account_number
 * @property string payment_receiver
 * @property string bank
 * @property string swift
 * @property string account_number
 * @property string goods_address_from
 * @property string goods_address_to
 * @property string details
 * @property string details1
 * @property string details_self
 * @property string details_bottom1
 * @property string details_bottom2
 * @property string details_bottom3
 * @property string document_signer
 * @property string document_partner_signer
 * @property int currency_id
 * @property int structuralunit_id
 * @property int invoicetype_id
 * @property float currency_rate
 * @property float amount_total
 * @property float amount_tatal_base_currency
 * @property bool is_locked
 * @property int locker_user_id
 */
class Invoice extends Model
{

	protected $table = 'invoices';
	protected $guarded = ['id'];

	public $timestamps = true;

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function partner()
	{
		return $this->belongsTo(Partner::class);
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

	public function structuralunit(){
		return $this->belongsTo(Structuralunit::class);
	}

	public function invoiceLines()
	{
		return $this->hasMany(InvoiceLine::class);
	}

    public function invoiceAdvancePayments(): HasMany
    {
        return $this->hasMany(InvoiceAdvancePayment::class);
    }

    public function invoiceLinesGroupedByVat()
    {
        return $this->hasMany(InvoiceLine::class)->groupBy('vat_id');
    }

	public function invoiceType()
	{
		return $this->belongsTo(InvoiceType::class, 'invoicetype_id');
	}

	public function setDateAttribute($value)
	{
		$this->attributes['date'] = \Carbon\Carbon::createFromFormat(
			'd.m.Y', $value
		)->format('Y-m-d');
	}

	public function setPaymentDateAttribute($value)
	{
		$this->attributes['payment_date'] = \Carbon\Carbon::createFromFormat(
			'd.m.Y', $value
		)->format('Y-m-d');
	}

	public function getDateAttribute($value)
	{
		return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format(
			'd.m.Y'
		);
	}

	public function getPaymentDateAttribute($value)
	{
		return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format(
			'd.m.Y'
		);
	}


	function getIsClosedForEditAttribute()
	{

		if (!(SelectedCompanyService::getCompany()->closed_data_date ?? null)) {
			return false;
		}

		$systemClosedDate = \Carbon\Carbon::createFromFormat(
			'd.m.Y',
			(SelectedCompanyService::getCompany()->closed_data_date ?? null)
		);
		$invoiceDate = \Carbon\Carbon::createFromFormat('d.m.Y', $this->date);
		if ($systemClosedDate->gte($invoiceDate)) {
			return true;
		}

		return false;
	}

	function getFullNameAttribute()
	{
		return $this->first_name.' '.$this->last_name;
	}


	// public function getCurrencyNameAttribute($value){
	//    return  $this->currency()->first()->name;
	// }

	// public function getPartnernameAttribute($value){
	//    return  $this->partner()->first()->name;
	// }

}

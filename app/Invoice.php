<?php

namespace App;

use App\Services\SelectedCompanyService;
use Illuminate\Database\Eloquent\Model;


class Invoice extends Model
{

	protected $table = 'invoices';
	protected $guarded = ['id'];
//	protected $fillable
//		= [
//			'number', 'date', 'vat_number', 'payment_date', 'company_id',
//			'structuralunit_id',
//			'invoicetype_id', 'partner_id', 'currency_id', 'currency_rate',
//			'amount_total',
//			'bank', 'swift', 'payment_receiver', 'account_number', 'details',
//			'details1', 'details_self',
//			'details_bottom1', 'details_bottom2', 'details_bottom3',
//			'document_signer', 'document_partner_signer',
//		];


//    protected $appends = ['is_closed_for_edit'];
	// public $appends = ['partnername', 'currency_name'];

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

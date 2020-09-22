<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invoice;
use App\Unit;
use App\Currency;
use App\Vat;

class InvoiceLine extends Model
{
	protected $table = 'invoice_lines';
	protected $fillable
		= [
			'invoice_id', 'product_id', 'title', 'unit_id', 'quantity',
			'currency_id', 'price', 'vat_id',
		];
	public $timestamps = true;

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function unit()
	{
		return $this->belongsTo(Unit::class);
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

	public function vat()
	{
		return $this->belongsTo(Vat::class);
	}
}

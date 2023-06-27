<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invoice;
use App\Unit;
use App\Currency;
use App\Vat;

class InvoiceAdvancePayment extends Model
{
	protected $table = 'invoice_advance_payments';
	protected $guarded = [];
	public $timestamps = true;

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = \Carbon\Carbon::createFromFormat(
            'd.m.Y', $value
        )->format('Y-m-d');
    }

    public function getDateAttribute($value): string
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format(
            'd.m.Y'
        );
    }
}

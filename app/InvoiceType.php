<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
	protected $table = 'invoice_types';
	protected $fillable = ['title'];

	public function getTitleAttribute($value)
	{
		return html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}


	// public $appends = ['partnername', 'currency_name'];

	public $timestamps = false;
}

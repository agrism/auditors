<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyVatNumber extends Model
{

	protected $table = 'company_vat_numbers';
	protected $fillable = ['vat_number', 'company_id'];
	public $timestamps = true;

}

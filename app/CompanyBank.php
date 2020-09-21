<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyBank extends Model
{
	protected $table = 'companies_bank';
	protected $fillable = ['company_id', 'payment_receiver', 'bank', 'swift', 'account_number'];
	public $timestamps = true;
}

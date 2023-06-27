<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PersonalIncomeTaxRate extends Model
{

	protected $table = 'personal_income_tax_rates';

	public $fillable = ['name', 'value'];
}

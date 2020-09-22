<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PersonalIncomeCostRate extends Model
{

	protected $table = 'personal_income_cost_rates';

	public $fillable = ['name', 'value'];
}


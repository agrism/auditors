<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PersonalIncomeType extends Model
{

	protected $table = 'personal_income_types';

	public $fillable = ['name'];
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Partner
 * @property int id
 * @property string name
 * @property string address
 * @property int company_id
 * @property string registration_number
 * @property string vat_number
 * @property string bank
 * @property string swift
 * @property string account_number
 */
class Partner extends Model
{
	protected $table = 'partners';
	protected $fillable
		= [
			'name', 'address', 'registration_number', 'vat_number', 'bank',
			'swift', 'account_number', 'company_id',
		];

	static function createRules()
	{
		return [
			'name' => 'required|min:2',
			'registration_number' => 'required|min:2',
		];
	}

	static function updateRules($id = null)
	{
		return [
			'name' => 'required|min:2',
			'registration_number' => 'required|min:2',
		];
	}
}

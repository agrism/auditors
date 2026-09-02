<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PersonalIncome extends Model
{

	protected $table = 'personal_incomes';

	public $fillable
		= [
			'partner_id', 'partner_name_text',
			'partner_registration_number_text', 'personal_income_type_id',
			'personal_income_type_text', 'income_period_date_from',
			'income_period_date_till', 'income_paid_date',
			'income_gross_amount',
			'personal_income_cost_rate_id', 'personal_income_cost_rate_text',
			'income_tax_rate_id', 'income_tax_rate_text',
			'creator_user_id', 'company_id',
		];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function partner()
	{
		return $this->belongsTo(Partner::class);
	}

	public function setIncomePeriodDateFromAttribute($value)
	{
		if (empty($value)) {
			$this->attributes['income_period_date_from'] = null;
			return;
		}

		try {
			$this->attributes['income_period_date_from']
				= \Carbon\Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
		} catch (\Exception $e) {
			$this->attributes['income_period_date_from']
				= \Carbon\Carbon::parse($value)->format('Y-m-d');
		}
	}

	public function getIncomePeriodDateFromAttribute($value)
	{
		if (empty($value)) {
			return null;
		}

		try {
			return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d.m.Y');
		} catch (\Exception $e) {
			try {
				return \Carbon\Carbon::parse($value)->format('d.m.Y');
			} catch (\Exception $e) {
				return $value;
			}
		}
	}

//--------------------
	public function setIncomePeriodDateTillAttribute($value)
	{
		if (empty($value)) {
			$this->attributes['income_period_date_till'] = null;
			return;
		}

		try {
			$this->attributes['income_period_date_till']
				= \Carbon\Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
		} catch (\Exception $e) {
			$this->attributes['income_period_date_till']
				= \Carbon\Carbon::parse($value)->format('Y-m-d');
		}
	}

	public function getIncomePeriodDateTillAttribute($value)
	{
		if (empty($value)) {
			return null;
		}

		try {
			return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d.m.Y');
		} catch (\Exception $e) {
			try {
				return \Carbon\Carbon::parse($value)->format('d.m.Y');
			} catch (\Exception $e) {
				return $value;
			}
		}
	}

//--------------------
	public function setIncomePaidDateAttribute($value)
	{
		if (empty($value)) {
			$this->attributes['income_paid_date'] = null;
			return;
		}

		try {
			$this->attributes['income_paid_date']
				= \Carbon\Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
		} catch (\Exception $e) {
			$this->attributes['income_paid_date']
				= \Carbon\Carbon::parse($value)->format('Y-m-d');
		}
	}

	public function getIncomePaidDateAttribute($value)
	{
		if (empty($value)) {
			return null;
		}

		try {
			return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d.m.Y');
		} catch (\Exception $e) {
			try {
				return \Carbon\Carbon::parse($value)->format('d.m.Y');
			} catch (\Exception $e) {
				return $value;
			}
		}
	}
}


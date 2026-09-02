<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Partner;
use App\CompanyVatNumber;

class Company extends Model
{
	protected $table = 'companies';
	protected $fillable
		= [
			'title', 'address', 'registration_number', 'bank', 'swift',
			'account_number', 'closed_data_date',
		];
	public $timestamps = true;

	public function users()
	{
		return $this->belongsToMany(User::class);
	}

	public function vatNumbers()
	{
		return $this->hasMany(CompanyVatNumber::class);
	}

	public function partners(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Partner::class)->orderBy('name', 'asc');
	}

	public function settings(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(CompanySetting::class);
	}


	public function setClosedDataDateAttribute($value)
	{
		if (isset($value) && $value) {
			try {
				$this->attributes['closed_data_date']
					= \Carbon\Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
			} catch (\Exception $e) {
				try {
					$this->attributes['closed_data_date']
						= \Carbon\Carbon::parse($value)->format('Y-m-d');
				} catch (\Exception $e) {
					$this->attributes['closed_data_date'] = null;
				}
			}
		} else {
			$this->attributes['closed_data_date'] = '1970-01-01';
		}
	}

	public function getClosedDataDateAttribute($value)
	{
		if ($value) {
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

		return null;
	}

	public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Employee::class);
	}

	public function structuralunits(){
		return $this->hasMany(Structuralunit::class);
	}

	public function banks()
	{
		return $this->hasMany(CompanyBank::class);
	}


}

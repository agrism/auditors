<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Partner;
use App\CompanyVatNumber;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $address
 * @property string $registration_number
 * @property string $bank
 * @property string $swift
 * @property string $account_number
 * @property string $closed_data_date
 * @property int $settings_top-margin
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Company extends Model
{
	protected $table = 'companies';
	protected $fillable
		= [
			'title', 'address', 'registration_number', 'bank', 'swift',
			'account_number', 'closed_data_date',
		];
	public $timestamps = true;

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class);
	}

	public function vatNumbers(): HasMany
	{
		return $this->hasMany(CompanyVatNumber::class);
	}

	public function partners(): HasMany
	{
		return $this->hasMany(Partner::class)->orderBy('name', 'asc');
	}

	public function settings(): HasMany
	{
		return $this->hasMany(CompanySetting::class);
	}


	public function setClosedDataDateAttribute($value): void
	{
		if (isset($value) && $value) {
			$this->attributes['closed_data_date']
				= Carbon::createFromFormat('d.m.Y', $value)->format(
				'Y-m-d'
			);
		} else {
			$this->attributes['closed_data_date']
				= Carbon::createFromFormat('d.m.Y', '01.01.1970')
				->format('Y-m-d');
		}
	}

	public function getClosedDataDateAttribute($value): ?string
	{
		if ($value) {
			return Carbon::createFromFormat('Y-m-d', $value)->format(
				'd.m.Y'
			);
		}

		return null;
	}

	public function employees(): HasMany
	{
		return $this->hasMany(Employee::class);
	}

	public function structuralunits(): HasMany
    {
		return $this->hasMany(Structuralunit::class);
	}

	public function banks(): HasMany
	{
		return $this->hasMany(CompanyBank::class);
	}
}

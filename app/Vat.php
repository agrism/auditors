<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vat extends Model
{
	protected $table = 'vats';
	protected $fillable = ['name', 'rate'];
	public $timestamps = false;

	public function scopeDefault($query)
	{
		return $query->where('default', 1)->first();
	}

}

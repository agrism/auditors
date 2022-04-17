<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
	protected $table = 'units';
	protected $fillable = ['name'];
	public $timestamps = true;

	public function scopeDefault($query)
	{
		return $query->where('default', 1)->first();
	}

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
	protected $table = 'calendars';
	protected $fillable
		= [];
	public $timestamps = true;
}

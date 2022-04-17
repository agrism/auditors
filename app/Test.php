<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
	protected $connection = 'sqlsrv';
	protected $table = 'Partner';
	protected $fillable = [];


}

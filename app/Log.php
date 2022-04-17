<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Partner;
use App\CompanyVatNumber;

class Log extends Model
{
	protected $table = 'logs';
	protected $fillable = ['ip', 'user_id','method', 'url', 'data',];
	public $timestamps = true;
}

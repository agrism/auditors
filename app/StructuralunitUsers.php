<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Structuralunit extends Model
{
	protected $table = 'structuralunits_users';
	public $timestamps = true;
	protected $fillable = ['structuralunit_id', 'useer_id'];



}

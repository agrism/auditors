<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Structuralunit extends Model
{
	protected $table = 'structuralunits';
	public $timestamps = true;
	protected $fillable = ['title', 'company_id'];

	public function getTitleAttribute($value)
	{
		return html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}

	public function invoices()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function users(){
		return $this->belongsToMany(User::class, 'structuralunits_users');
	}

}

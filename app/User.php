<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable
		= [
			'name', 'email', 'password',
		];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden
		= [
			'password', 'remember_token',
		];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts
		= [
			'email_verified_at' => 'datetime',
		];

	public function roles()
	{

		return $this->belongsToMany(Role::class)->withPivot('partner_id');
	}

	public function assignRole(Role $role, $partnerId)
	{
		return $this->roles()->save($role, ['partner_id' => $partnerId]);
	}


	public function hasRole($role)
	{
		if (is_string($role)) {
			return $this->roles->contains('name', $role);
		}

		// return  !! $role->intersect($this->roles)->count();
		foreach ($role as $r) {
			if ($this->hasRole($r->name)) {
				return true;
			}
		}

		// if( $this->hasRole($role->name) ){
		//     return true;
		// }
	}

	public function partners()
	{
		return $this->belongsToMany(Partner::class);
	}

	public function companies()
	{
		return $this->belongsToMany(Company::class);
	}

	public function scopeAdmin($query)
	{
		return $query->where('is_admin', 1);
	}

	public function isAdmin()
	{
		return $this->is_admin;
	}

	public function structuralunits(){
		return $this->belongsToMany(Structuralunit::class, 'structuralunits_users');
	}
}

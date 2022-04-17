<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
	protected $table = 'roles';
	protected $fillable = ['name', 'label'];

	public function permissions()
	{
		return $this->belongsToMany(Permission::class);
	}

	public function givePermissionTo(Permission $permission)
	{
		return $this->permissions()->save($permission);
	}


}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tpost;

class Tcategory extends Model
{
	protected $table = 'tcategories';

	public function tpost()
	{
		return $this->hasMany('App\Tpost');
	}
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tcategory;

class Tpost extends Model {
	protected $table = 'tposts';

	public function tcategory(){
		return $this->belongsTo('App\Tcategory');
	}
}
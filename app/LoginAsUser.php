<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LoginAsUser
 * @property string secret
 * @property string user_id
 */
class LoginAsUser extends Model
{
	use SoftDeletes;

	protected $table = 'login_as_user';


}
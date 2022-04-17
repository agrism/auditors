<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Partner
 *
 * @property int    id
 * @property string name
 * @property string code
 */
class Account extends Model
{
    protected $table    = 'accounts';
    protected $fillable = [
        'name',
        'code',
        'company_id',
    ];
}
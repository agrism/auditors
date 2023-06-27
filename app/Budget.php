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
class Budget extends Model
{
    protected $table    = 'budgets';
    protected $fillable = [
        'name',
        'code',
        'company_id',
    ];
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee
 *
 * @property int    id
 * @property string name
 * @property string role
 * @property int    company_id
 * @property string registration_number
 */
class Employee extends Model
{
    protected $table    = 'employees';
    protected $fillable = [
        'name',
        'role',
        'registration_number',
        'company_id',
    ];

    static function createRules()
    {
        return [
            'name'                => 'required|min:2',
            'registration_number' => 'required|min:2',
        ];
    }

    static function updateRules($id = null)
    {
        return [
            'name'                => 'required|min:2',
            'registration_number' => 'required|min:2',
        ];
    }
}
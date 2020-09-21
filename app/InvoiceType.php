<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    protected $table = 'invoice_types';
    protected $fillable = ['title'];


    // public $appends = ['partnername', 'currency_name'];

    public $timestamps = false;
}

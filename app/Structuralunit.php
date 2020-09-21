<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Structuralunit extends Model
{
    protected $table ='structuralunits';
    public $timestamps =true;
    protected $fillable = ['title', 'company_id'];

    public function invoices(){
        return $this->belongsTo(Invoice::class);
    }

}

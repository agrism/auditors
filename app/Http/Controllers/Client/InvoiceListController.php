<?php

namespace App\Http\Controllers\Client;

use App\Company;
use Illuminate\Http\Request;

class InvoiceListController extends Company
{
    public function index(){
        return view('client.invoices.list');
    }
}

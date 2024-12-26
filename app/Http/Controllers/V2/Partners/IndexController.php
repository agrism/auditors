<?php

namespace App\Http\Controllers\V2\Partners;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('v2.partners.index');
    }
}

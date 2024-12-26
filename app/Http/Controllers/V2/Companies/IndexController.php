<?php

namespace App\Http\Controllers\V2\Companies;

use App\Http\Controllers\Controller;
use App\Services\V2\UserCompanyHelper;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(Request $request): View
    {
        $companies = UserCompanyHelper::instance()->getUserCompanies()->sortBy('title');

        return view('v2.companies.index', compact('companies'));
    }
}

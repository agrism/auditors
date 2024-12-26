<?php

namespace App\Http\Controllers\V2\Companies;

use App\Company;
use App\Http\Controllers\Controller;
use App\Services\V2\UserCompanyHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ActivateController extends Controller
{
    public function __invoke(Request $request, int $id): Redirect|string
    {
        /** @var Company $company */
        if(!$company = Company::query()->where('id', $id)->first()){
            return redirect()->route('v2.index');
        }

        if(!UserCompanyHelper::instance()->setSelectedCompany($company)){
            return redirect()->route('v2.index');
        }

        $toSwap = '<div hx-swap-oob="true:#selected-company-name">'.$company->title.'</div>';
sleep(1);
        return view('components.v2.client.customer-list').$toSwap;
    }
}

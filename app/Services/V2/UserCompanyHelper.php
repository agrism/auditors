<?php

namespace App\Services\V2;

use App\Company;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class UserCompanyHelper
{
    use InstanceTrait;

    /** @var null|Collection<Company> */
    protected ?Collection $userCompanies = null;

    public function getSelectedCompany(): ?Company
    {
        return session()->get('selectedCompany');
    }

    public function setSelectedCompany(Company $company): bool
    {
        if (!$this->getUserCompanies()->contains($company)) {
            return false;
        }

        session()->put('selectedCompany', $company);
        return true;
    }

    /** @return Collection<Company> */
    public function getUserCompanies(): Collection
    {
        if (!Auth::check()) {
            return new Collection();
        }

        if (!$this->userCompanies) {
            $this->userCompanies = Auth::user()->companies()->get();
        }

        return $this->userCompanies;
    }

}

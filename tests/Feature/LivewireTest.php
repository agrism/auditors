<?php

namespace Tests\Feature;

use App\Company;
use App\Http\Livewire\CompanyList;
use App\Http\Livewire\InvoiceList;
use App\Http\Livewire\MainApp;
use App\Http\Livewire\PartnerList;
use App\User;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireTest extends TestCase
{
    protected function getTestUser(): ?User
    {
        return User::where('email', '7924@inbox.lv')->first() ?? User::first();
    }

    protected function getTestCompany(): ?Company
    {
        return Company::first();
    }

    public function test_livewire_main_app(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $this->actingAs($user);
        session(['companyId' => $company->id]);

        Livewire::test(MainApp::class)
            ->assertStatus(200);
    }

    public function test_livewire_company_list(): void
    {
        $user = $this->getTestUser();
        $this->actingAs($user);

        Livewire::test(CompanyList::class)
            ->assertStatus(200);
    }

    public function test_livewire_invoice_list(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $this->actingAs($user);
        session(['companyId' => $company->id]);

        Livewire::test(InvoiceList::class, ['activeCompanyId' => $company->id])
            ->assertStatus(200);
    }

    public function test_livewire_partner_list(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $this->actingAs($user);
        session(['companyId' => $company->id]);

        Livewire::test(PartnerList::class, ['activeCompanyId' => $company->id])
            ->assertStatus(200);
    }
}

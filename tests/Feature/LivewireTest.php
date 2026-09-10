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

    public function test_livewire_cash_expenses_list(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $this->actingAs($user);
        session(['companyId' => $company->id]);

        Livewire::test(\App\Http\Livewire\CashExpenses\CashExpensesList::class)
            ->assertStatus(200);
    }

    public function test_livewire_user_profile(): void
    {
        $user = $this->getTestUser();
        $this->actingAs($user);

        Livewire::test(\App\Http\Livewire\UserProfile::class)
            ->assertStatus(200)
            ->assertSet('name', $user->name)
            ->assertSet('email', $user->email);
    }

    public function test_livewire_main_app_global_search(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $this->actingAs($user);
        session(['companyId' => $company->id]);

        Livewire::test(MainApp::class)
            ->assertStatus(200)
            ->set('globalSearchQuery', mb_substr($company->title, 0, 3))
            ->assertSee($company->title)
            ->call('clearGlobalSearch')
            ->assertSet('globalSearchQuery', '');
    }

    public function test_livewire_main_app_title_format(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $this->actingAs($user);
        session(['companyId' => $company->id]);

        Livewire::test(MainApp::class)
            ->assertStatus(200)
            ->assertSee('Auditors.lv :: SĀKUMS')
            ->assertSeeHtml('badge-invoices')
            ->assertSeeHtml('badge-partners')
            ->assertSeeHtml('badge-expenses')
            ->assertSeeHtml('badge-vacations')
            ->call('activateComponent', 'invoices')
            ->assertSee('Auditors.lv :: RĒĶINI')
            ->call('activateComponent', 'partners')
            ->assertSee('Auditors.lv :: PARTNERI')
            ->call('activateComponent', 'cash-expenses')
            ->assertSee('Auditors.lv :: AVANSU NORĒĶINI');
    }
}


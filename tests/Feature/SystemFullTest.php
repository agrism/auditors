<?php

namespace Tests\Feature;

use App\Company;
use App\Http\Livewire\CompanyList;
use App\Http\Livewire\InvoiceList;
use App\Http\Livewire\MainApp;
use App\Http\Livewire\PartnerList;
use App\Invoice;
use App\Partner;
use App\User;
use Livewire\Livewire;
use Tests\TestCase;

class SystemFullTest extends TestCase
{
    protected function getTestUser(): ?User
    {
        return User::where('email', '7924@inbox.lv')->first() ?? User::first();
    }

    protected function getTestCompany(): ?Company
    {
        return Company::first();
    }

    public function test_database_has_seeded_data(): void
    {
        $this->assertGreaterThan(0, User::count(), 'Users table is empty');
        $this->assertGreaterThan(0, Company::count(), 'Companies table is empty');
        $this->assertGreaterThan(0, Invoice::count(), 'Invoices table is empty');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/client/companies');
        $response->assertRedirect('/login');
    }

    public function test_client_endpoints_redirect_to_main_livewire_app(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();
        $this->assertNotNull($user, 'Test user not found');
        $this->assertNotNull($company, 'Test company not found');

        $response = $this->actingAs($user)->get('/client/companies');
        $response->assertRedirect('client/new');

        $responseInvoices = $this->actingAs($user)
            ->withSession(['companyId' => $company->id])
            ->get('/client/invoices');
        $responseInvoices->assertRedirect('/client/new');

        $responsePartners = $this->actingAs($user)
            ->withSession(['companyId' => $company->id])
            ->get('/client/partners');
        $responsePartners->assertRedirect('/client/new');
    }

    public function test_invoice_details_view(): void
    {
        $user = $this->getTestUser();
        $invoice = Invoice::first();
        $this->assertNotNull($invoice, 'No invoice found in database');

        $response = $this->actingAs($user)
            ->withSession(['companyId' => $invoice->company_id])
            ->get("/client/invoices/{$invoice->id}");

        $response->assertStatus(200);
    }

    public function test_company_bank_and_settings(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $responseBank = $this->actingAs($user)
            ->withSession(['companyId' => $company->id])
            ->get('/client/companies/bank');
        $responseBank->assertStatus(200);

        $responseSettings = $this->actingAs($user)
            ->withSession(['companyId' => $company->id])
            ->get('/client/companies/settings');
        $responseSettings->assertStatus(200);
    }

    public function test_personal_incomes_module(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $response = $this->actingAs($user)
            ->withSession(['companyId' => $company->id])
            ->get('/client/personal-incomes');

        $response->assertStatus(200);
    }

    public function test_livewire_main_app_endpoint(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $response = $this->actingAs($user)
            ->withSession(['companyId' => $company->id])
            ->get('/client/new');

        $response->assertStatus(200);
    }

    public function test_admin_access_allowed_for_admin_user(): void
    {
        $admin = User::where('is_admin', 1)->first();
        $this->assertNotNull($admin, 'Admin user not found');

        $responseHome = $this->actingAs($admin)->get('/admin');
        $responseHome->assertStatus(200);

        $responseCompanies = $this->actingAs($admin)->get('/admin/companies');
        $responseCompanies->assertStatus(200);

        $responseUsers = $this->actingAs($admin)->get('/admin/users');
        $responseUsers->assertStatus(200);

        $responseRoles = $this->actingAs($admin)->get('/admin/roles');
        $responseRoles->assertStatus(200);

        $responseVat = $this->actingAs($admin)->get('/admin/vat');
        $responseVat->assertStatus(200);

        $responseInvoicesPage1 = $this->actingAs($admin)->get('/admin/invoices?page=1');
        $responseInvoicesPage1->assertStatus(200);

        $responseInvoicesPage2 = $this->actingAs($admin)->get('/admin/invoices?page=2');
        $responseInvoicesPage2->assertStatus(200);
    }

    public function test_admin_access_forbidden_for_non_admin_user(): void
    {
        $nonAdmin = User::where('is_admin', 0)->orWhereNull('is_admin')->first();
        if ($nonAdmin) {
            $response = $this->actingAs($nonAdmin)->get('/admin');
            $response->assertRedirect('/client');
        }
    }
}

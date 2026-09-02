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

    public function test_invoice_deletion_cleans_foreign_keys(): void
    {
        $company = $this->getTestCompany();
        $partner = \App\Partner::first();
        $currency = \App\Currency::first();
        $type = \App\InvoiceType::first();

        // Create a test invoice
        $invoice = \App\Invoice::create([
            'company_id' => $company->id,
            'partner_id' => $partner ? $partner->id : 1,
            'currency_id' => $currency ? $currency->id : 1,
            'invoicetype_id' => $type ? $type->id : 1,
            'number' => 'TEST-DEL-001',
            'date' => '01.01.2025',
            'amount_total' => 121.00,
        ]);

        // Insert into invoice_lines
        \Illuminate\Support\Facades\DB::table('invoice_lines')->insert([
            'invoice_id' => $invoice->id,
            'title' => 'Test Line',
            'price' => 100,
            'quantity' => 1,
            'vat_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert into invoice_lines_2 if table exists
        if (\Illuminate\Support\Facades\Schema::hasTable('invoice_lines_2')) {
            \Illuminate\Support\Facades\DB::table('invoice_lines_2')->insert([
                'invoice_id' => $invoice->id,
                'title' => 'Test Line 2',
                'price' => 100,
                'quantity' => 1,
                'vat_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Delete using InvoiceService
        $service = app(\App\Services\InvoiceService::class);
        $service->deleteInvoice($company, $invoice->id);

        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
        $this->assertDatabaseMissing('invoice_lines', ['invoice_id' => $invoice->id]);
        if (\Illuminate\Support\Facades\Schema::hasTable('invoice_lines_2')) {
            $this->assertDatabaseMissing('invoice_lines_2', ['invoice_id' => $invoice->id]);
        }
    }
}


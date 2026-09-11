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
            ->assertSee('Auditors.lv :: AVANSU NORĒĶINI')
            ->call('activateComponent', 'feedback')
            ->assertSee('Auditors.lv :: MANAS SAZIŅAS');
    }

    public function test_livewire_bug_reports_list(): void
    {
        $user = $this->getTestUser();
        $this->actingAs($user);

        Livewire::test(\App\Http\Livewire\BugReportsList::class)
            ->assertStatus(200)
            ->assertSee('Jauns paziņojums / ieteikums');
    }

    public function test_bug_report_status_enum_and_history_tracking(): void
    {
        $user = $this->getTestUser();
        $this->actingAs($user);

        // 1. Create report via store endpoint
        $response = $this->postJson(route('bug-reports.store'), [
            'description' => 'Testa paziņojums kļūdas pārbaudei',
            'section' => 'Rēķini',
            'email' => 'test@auditors.lv',
        ]);
        $response->assertStatus(200)->assertJson(['success' => true]);

        $reportId = $response->json('report_id');
        $report = \App\BugReport::with(['statuses', 'items'])->findOrFail($reportId);

        $this->assertEquals(\App\Enums\BugReportStatus::NEW, $report->status);
        $this->assertEquals('new', $report->status_value);
        $this->assertCount(1, $report->statuses);
        $this->assertEquals(\App\Enums\BugReportStatus::NEW, $report->statuses->first()->status);

        // 2. Change status through Livewire / method
        $report->changeStatus(\App\Enums\BugReportStatus::IN_PROGRESS, $user->id, 'Izskatīšana sākta');
        $report->refresh();

        $this->assertEquals(\App\Enums\BugReportStatus::IN_PROGRESS, $report->status);
        $this->assertCount(2, $report->statuses);
        $this->assertEquals('Izskatīšanā', $report->status->label());
        $this->assertEquals('Izskatīšana sākta', $report->latestStatusRecord->comment);

        // 3. Test Livewire close / reopen action
        Livewire::test(\App\Http\Livewire\BugReportsList::class)
            ->call('closeTicket', $report->id);

        $report->refresh();
        $this->assertEquals(\App\Enums\BugReportStatus::CLOSED, $report->status);
        $this->assertCount(3, $report->statuses);
        $this->assertEquals('Slēgts', $report->status->label());

        Livewire::test(\App\Http\Livewire\BugReportsList::class)
            ->call('reopenTicket', $report->id);

        $report->refresh();
        $this->assertEquals(\App\Enums\BugReportStatus::IN_PROGRESS, $report->status);
        $this->assertCount(4, $report->statuses);
    }

    public function test_unread_messages_badge_and_read_state_tracking(): void
    {
        $user = $this->getTestUser();
        $this->actingAs($user);

        \App\BugReport::where('user_id', $user->id)->delete();

        // 1. User creates report - should have 0 unread messages
        $report = \App\BugReport::create([
            'user_id' => $user->id,
            'section' => 'Rēķini',
            'status' => \App\Enums\BugReportStatus::NEW,
            'last_read_at' => now(),
        ]);
        $report->items()->create([
            'user_id' => $user->id,
            'message' => 'Lietotāja sākotnējais jautājums',
            'is_admin_reply' => false,
        ]);

        $this->assertEquals(0, \App\BugReport::unreadCountForUser($user->id));
        $this->assertFalse($report->isUnreadForClient());

        // 2. Admin posts a reply - report should now be unread for the user
        sleep(1); // ensure timestamp difference
        $adminUser = \App\User::where('is_admin', 1)->first() ?? $user;
        $report->items()->create([
            'user_id' => $adminUser->id,
            'message' => 'Administrators atbildēja uz jautājumu',
            'is_admin_reply' => true,
        ]);

        $report->refresh();
        $this->assertTrue($report->isUnreadForClient());
        $this->assertEquals(1, \App\BugReport::unreadCountForUser($user->id));

        // 3. MainApp should render the badge
        Livewire::test(\App\Http\Livewire\MainApp::class)
            ->assertSee('Manas saziņas')
            ->assertSeeHtml('<span class="badge bg-danger rounded-pill');

        // 4. BugReportsList should show unread highlight
        Livewire::test(\App\Http\Livewire\BugReportsList::class)
            ->assertSee('JAUNA ATBILDE')
            ->call('openReport', $report->id);

        // 5. After opening the report, it should be marked as read
        $report->refresh();
        $this->assertFalse($report->isUnreadForClient());
        $this->assertEquals(0, \App\BugReport::unreadCountForUser($user->id));
    }

    public function test_main_app_maintains_active_component_on_events(): void
    {
        $user = $this->getTestUser();
        $company = $this->getTestCompany();

        $this->actingAs($user);
        session(['companyId' => $company->id]);

        Livewire::test(MainApp::class)
            ->call('activateComponent', 'feedback')
            ->assertSet('nav.feedback.active', true)
            ->assertSee('Auditors.lv :: MANAS SAZIŅAS')
            ->emit('unreadBugReportsUpdated')
            ->assertSet('nav.feedback.active', true)
            ->assertSee('Auditors.lv :: MANAS SAZIŅAS');
    }

    public function test_livewire_bug_report_reply_queues_email(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $user = $this->getTestUser();
        $this->actingAs($user);

        $report = \App\BugReport::create([
            'user_id' => $user->id,
            'section' => 'Rēķini',
            'status' => \App\Enums\BugReportStatus::NEW,
            'last_read_at' => now(),
        ]);
        $report->items()->create([
            'user_id' => $user->id,
            'message' => 'Pirmais jautājums',
            'is_admin_reply' => false,
        ]);

        Livewire::test(\App\Http\Livewire\BugReportsList::class)
            ->call('openReport', $report->id)
            ->set('replyMessage', 'Klienta papildu jautājums no Livewire')
            ->call('sendReply');

        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\NewBugReportMessageMail::class, function ($mail) {
            return $mail->hasTo('7924@inbox.lv') && $mail->isNewThread === false;
        });
    }
}

